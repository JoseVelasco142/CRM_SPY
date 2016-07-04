<?php

namespace app\controllers;
use app\models\SpyClient;
use app\models\SpyCommercial;
use app\models\SpyFile;
use app\models\SpyGuide;
use app\models\SpyNote;
use app\models\SpyTask;
use app\models\SpyTaskType;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/files';
class CommercialController extends \yii\web\Controller
{

    public $layout = "main";

    public function beforeAction($action){
        if ($action->id == 'file-upload') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionCalendar(){
        $app = Yii::$app;
        if($app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('calendar');
    }

    public function actionPopulateCalendar(){
        $app = Yii::$app;
        if(Yii::$app->request->isAjax){
            $user = $app->user->getId();
            $calendarTasks = [];
            $tasksUp = SpyTask::find()
                ->joinWith('client')
                ->where(['spy_task.state' => 1])
                ->andWhere(['spy_client.commercial_id' => $user])
                ->all();
            foreach($tasksUp as $task){
                if($task instanceof SpyTask){
                    $type = $task->taskType;
                    array_push($calendarTasks, [
                        'id' => $task->task_id,
                        'title' => $task->subject,
                        'start' => $task->alert,
                        'alert' => $task->alert,
                        'creation' => $task->date,
                        'backgroundColor' => $type->color,
                        'textColor' => 'black',
                        'allDay' => false,
                    ]);
                }
            }
            $allNotes = SpyNote::find()
                ->where(['commercial_id' => $user])
                ->orWhere(['commercial_id' => 2] )
                ->andWhere(['state' => true])
                ->all();
            foreach($allNotes as $note){
                if($note instanceof SpyNote){
                    $event = [
                        'note' => true,
                        'id' => $note->note_id,
                        'title' => $note->short_description,
                        'text' => $note->text,
                        'start' => $note->date,
                        'alert' => $note->date,
                        'shared' => $note->shared,
                        'textColor' => 'black',
                        'allDay' => true
                    ];
                    !$note->shared ? $event['backgroundColor'] = 'rgb(209, 209, 63)' : $event['backgroundColor'] = 'rgb(115, 156, 255)';
                    array_push($calendarTasks, $event);
                }
            }
            $allTypes = SpyTaskType::find()->all();
            $app->response->format = Response::FORMAT_JSON;
            return [
                'calendar' =>  $calendarTasks,
                'taskTypes' => $allTypes,
            ];
        }
        return null;
    }

    /*
     * @return statistics of all clients
     */
    public function actionGetClientsStatistics(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $data = [];
            $clients = SpyClient::find()->where(['commercial_id' => $app->user->getId()])->all();
            foreach($clients as $client){
                if($client instanceof SpyClient){
                    $cnt = $client->tasksCount;
                    if($cnt>0){
                        array_push($data, [
                            'y' => $client->name,
                            'a' => $client->tasksCount
                        ]);
                    }
                }
            }
            $res->format = Response::FORMAT_JSON;
            return $data;
        }
        return null;
    }

    /*
     * show guides view
     */
    public function actionGuides(){
        $app = Yii::$app;
        if($app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('guides', [
           'guides' => SpyGuide::find()->all(),
        ]);
    }

    /*
     * show account view
     */
    public function actionAccount(){
        $app = Yii::$app;
        if($app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('account', [
            'commercial' => SpyCommercial::findOne($app->user->id),
        ]);
    }

    /*
     * change password
     */
    public function actionChangePassword(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $commercial = SpyCommercial::findOne($app->user->id);
            if($commercial instanceof SpyCommercial){
                if($req->post('current') == $commercial->password){
                    $commercial->password = $req->post('new_pass');
                    return $commercial->save(false);
                } else {
                    return false;
                }
            }

        }
        return null;
    }


    /*
   *  render files view
   */
    public function actionFiles(){
        $app = Yii::$app;
        if($app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('files', [
            'files' => SpyFile::find()->where(['commercial_id' => $app->user->id])->all(),
        ]);
    }

    /*
  * @return file
  */
    public function actionGetFile(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isGet){
            $model = SpyFile::findOne(['file_id' => $req->get('id')]);
            if($model instanceof SpyFile){
                $path = $model->path;
                if(file_exists($path)){
                    $handle = fopen($path, 'rb');
                    $options['mimeType'] = FileHelper::getMimeTypeByExtension($path);
                    return $res->sendStreamAsFile($handle, $model->short_description, $options);
                }
            }
        }
        return null;
    }

    /*
     * remove file from guide
    * @return file
    */
    public function actionRemoveFile(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $file = SpyFile::findOne(['file_id' => $req->post('id')]);
            if($file instanceof SpyFile){
                unlink($file->path);
                return $file->delete();
            }
        }
        return null;
    }

    /*
     * upload file to guide
     */
    public function actionFileUpload(){
        $app = Yii::$app;
        $req = $app->request;
        if ($req->isPost && isset($_FILES['file'])) {
            $path = $app->params['uploadPath']. "/" . "commercials";
            $file = UploadedFile::getInstanceByName('file');
            $originalName = $file->name;
            $generatedName = $app->security->generateRandomString().".".$file->extension;
            $_file = new SpyFile();
            $_file->short_description = $originalName;
            $_file->path = $path . "/" . $generatedName;
            $_file->commercial_id = $app->user->id;
            if($_file->save()){
                if ($file->saveAs($path . "/" . $generatedName)) {
                    return true;
                }
            }
        }
        return null;
    }

    /*
     *  render maps view
     */
    public function actionMaps(){
        $app = Yii::$app;
        if($app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('maps');
    }

    /*
     * return geo-located clients
     */
    public function actionGetGeoLocatedClients(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $session = $app->session;
        if($req->isAjax){
            $clientsList = [];
            $clients = SpyClient::find()
                ->select(['client_id', 'name', 'coordinates'])
                ->where(['commercial_id' => $app->user->id])
                ->andWhere(['!=', 'coordinates', ''])
                ->all();
            foreach($clients as $client){
                if($client instanceof SpyClient){
                    $item = [
                        'client_id' => $client->client_id,
                        'name' => $client->name,
                        'coordinates' => $client->coordinates,
                    ];
                    in_array($client->client_id, $session->get('routes')) ? $item['route'] = true : $item['route'] = false ;
                    array_push($clientsList, $item);
                }
            }
            $res->format = Response::FORMAT_JSON;
            return $clientsList;
        }
        return null;
    }



}
