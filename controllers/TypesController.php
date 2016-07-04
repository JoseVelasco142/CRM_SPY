<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\SpyCategory;
use app\models\SpyDepartment;
use app\models\SpyFaculty;
use app\models\spyPosition;
use app\models\SpySector;

class TypesController extends Controller
{
    public function actionCreate(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $model = null;
        if($req->isAjax){
            $data = $req->post();
            switch($data['type']){
                case "position":
                    $model = new SpyPosition();
                    break;
                case "category":
                    $model = new SpyCategory();
                    break;
                case "sector":
                    $model = new SpySector();
                    break;
                case "department":
                    $model = new SpyDepartment();
                    break;
            }
            $model->name = $data['name'];
            $exist = $model->find()->where(['LIKE', 'name', $data['name']])->all();
            $res->format = Response::FORMAT_JSON;
            if(!$exist && $model->save(false)){
                return [
                    'validate' => true,
                    'item' => [
                        'id' => $model->getPrimaryKey(),
                        'name' => $model->name,
                    ],
                ];
            } else {
                return [
                    'validate' => false,
                    'exist' => $exist,
                ];
            }
        }
        return null;
    }

    public function actionCreateFaculty(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $model = null;
        if($req->isAjax){
            $data = $req->post();
            $model = new SpyFaculty();
            $model->name = $data['name'];
            $model->address = $data['address'];
            $model->city = $data['city'];
            $model->province = $data['province'];
            !in_array('postalCode', $data) ? $model->postal_code = 00000 : $model->postal_code = $data['postalCode'];
            $model->coordinates = $data['coordinates'];
            $res->format = Response::FORMAT_JSON;
            if($model->save(false)){
                return [
                    'item' => [
                        'id' => $model->getPrimaryKey(),
                        'name' => $model->name,
                    ],
                ];
            }
        }
        return null;
    }

    public function actionGetFacultyLocationData(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $model = null;
        if($req->isAjax){
            $data = $req->post();
            $faculty = SpyFaculty::findOne(['faculty_id' => $data['id']]);
            $res->format = Response::FORMAT_JSON;
            if($faculty instanceof SpyFaculty)
            return [
                'address' => $faculty->address,
                'city' => $faculty->city,
                'province' => $faculty->province,
                'postalCode' => $faculty->postal_code,
                'coordinates' => $faculty->coordinates,
            ];
        }
        return null;
    }
}
