<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 10/05/2016
 * Time: 10:29
 */

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;


class ClientsParser extends Model {

    public $commercial;
    public $file;
    public $path;

    public function rules(){
        return [
            ['commercial', 'required', 'message' => 'Selecciona un comercial'],
            [['file'],'string'],
        ];
    }

    public function attributeLabels(){
        return [
            'commercial' => 'Comercial',
            'file' => 'Listado',
        ];
    }

    public function parseClient(){
        $app = Yii::$app;
        $app->params['uploadPath'] = $app->basePath . '/files/parser/';
        $file = UploadedFile::getInstance($this, 'file');
        if (empty($file))
            return null;
        $this->path = $app->params['uploadPath'].$file->getBaseName().".".$file->getExtension();
        $file->saveAs($this->path);
        $excel = \PHPExcel_IOFactory::load($this->path);
        $data = $excel->getActiveSheet()->toArray(null, false, true, false);
        $clientList = [];
        $CLIENT_NAME = 0;
        $CONTACT_NAME = 1; $CONTACT_PHONE = 2; $CONTACT_MAIL = 5;
        $SECOND_CONTACT_PHONE = 3; $SECOND_CONTACT_MAIL = 6;
        $CATEGORY = 7; $SECTOR = 8;
        $CITY = 9; $ADDRESS = 10; $CP = 11;
        $COMMENT = 13;
        $FAX = 4; $WEB = 12;
        foreach($data as $k => $client){
            if($k>=2){
                if($client[0] == "###END"){
                    break;
                }
                $cli = [
                    'client_name' => $client[$CLIENT_NAME],
                    'contact_name' => $client[$CONTACT_NAME],
                    'contact_phone' => $client[$CONTACT_PHONE],
                    'contact_mail' => $client[$CONTACT_MAIL],
                    'second_contact_name' => "Sin definir",
                    'second_contact_phone' => $client[$SECOND_CONTACT_PHONE],
                    'second_contact_mail' => $client[$SECOND_CONTACT_MAIL],
                    'category' => $client[$CATEGORY],
                    'sector' => $client[$SECTOR],
                    'city' => $client[$CITY],
                    'address' => $client[$ADDRESS],
                    'cp' => $client[$CP],
                    'comment' => $client[$COMMENT],
                ];
                if(!empty($client[$CONTACT_PHONE])){
                    $phone_split = explode(" ",$client[$CONTACT_PHONE]);
                    $final_phone = "";
                    if(count($phone_split) > 1){
                        foreach($phone_split as $part){
                            $final_phone .= $part;
                        }
                        $cli['contact_phone'] = $final_phone;
                    }
                }
                if(!empty($client[$SECOND_CONTACT_PHONE])){
                    $phone_split = explode(" ",$client[$SECOND_CONTACT_PHONE]);
                    $final_phone = "";
                    if(count($phone_split) > 1){
                        foreach($phone_split as $part){
                            $final_phone .= $part;
                        }
                        $cli['second_contact_phone'] = $final_phone;
                    }
                }
                if(empty($client[$CATEGORY])){
                    $cli['category'] = 1;
                }
                if(empty($client[$SECTOR])){
                    $cli['sector'] = 1;
                }
                if(!empty($client[$COMMENT])){
                    $data_formatted = str_replace("##",""."\n"."",$client[13]);
                    $cli['comment'] = $data_formatted;
                }
                if(!empty($client[$WEB])){
                    $cli['comment'] = $cli['comment']. "\n" . "- Web: ".$client[$WEB];
                }
                if(!empty($client[$FAX])){
                    $cli['comment'] = $cli['comment']. "\n" . "- Fax: ".$client[$FAX];
                }
                array_push($clientList, $cli);
            }
        }
        return $clientList;
    }

}