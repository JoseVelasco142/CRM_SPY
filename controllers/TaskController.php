<?php

namespace app\controllers;
use app\models\SpyClient;
use app\models\SpyFile;
use app\models\SpyNote;
use app\models\spyPosition;
use app\models\SpyTask;
use app\models\SpyTaskType;
use app\models\taskDiary;
use Faker\Provider\File;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use \yii\web\UploadedFile;

Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/files';

class TaskController extends Controller
{

    public function beforeAction($action){
        if ($action->id == 'file-upload' || $action->id == 'temp-store-file') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $app = Yii::$app;
        $req = $app->request;
        if($app->user->isGuest){
            return $this->goHome();
        }
        $model = new TaskDiary();
        $params = $req->queryParams;
        if(count($params)>1){
            $model->category = $params['category'];
            $model->sector = $params['sector'];
            $model->faculty = $params['faculty'];
            $model->department = $params['department'];
            $model->client = $params['client'];
            $model->type = $params['type'];
            $model->date = $params['date'];
        }
        if($model->load($req->get())){
            return $this->render('index',[
                'model' => $model,
                'taskList' => $model->search(),
            ]);
        }

        return $this->render('index',[
            'model' => $model,
            'taskList' =>  $model->search(),
        ]);
    }

    /**
     * update task description
     * @return boolean
     */
    public function actionUpdateDesc(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $data = $req->post();
            $task = SpyTask::findOne(['task_id' => $data['id']]);
            if($task instanceof SpyTask){
                $task->description = $data['text'];
                return $task->update();
            };
            return null;
        }
        return null;
    }

    /**
     * finalize task and save report
     * @return boolean
     */
    public function actionFinalize(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $data = $req->post();
            $task = SpyTask::findOne(['task_id' => $data['id']]);
            if($task instanceof SpyTask){
                $data['report']=="Escribe un reporte si es necesario. (OPCIONAL)" ? $data['report']="" : null;
                $task->report = $data['report'];
                $task->state = 0;
                $task->date = date('Y-m-d H:i:s');
                $task->setNewState($task->client);
                if($task->taskT_id ==6){
                    $courtesyCall = new SpyTask();
                    $courtesyCall->subject = "Preguntar por: ".$task->subject;
                    $courtesyCall->description = "¿Que tal le va?"."\n".$task->subject."\n".$task->description;
                    $courtesyCall->taskT_id = 7;
                    $courtesyCall->alert = date('Y-m-d H:i:s', strtotime('+30 days'));
                    $courtesyCall->client_id = $task->client_id;
                    $courtesyCall->save(false);
                }
                return $task->save(false);
            }
            return null;
        }
        return null;
    }

    /*
     * upload task files
     * @return boolean each file
     */
    public function actionFileUpload(){
        $app = Yii::$app;
        $req = $app->request;
        if ($req->isPost && isset($_FILES['file'])) {
            $path = $app->params['uploadPath']. "/" . "tasks";
            $taskId = $req->get('id');
            $file = UploadedFile::getInstanceByName('file');
            $originalName = $file->name;
            $generatedName = $app->security->generateRandomString().".".$file->extension;
            $task = new SpyFile();
            $task->short_description = $originalName;
            $task->path = $path . "/" . $generatedName;
            $task->task_id = $taskId;
            if($task->save()){
                if ($file->saveAs($path . "/" . $generatedName)) {
                    return true;
                }
            }
        }
        return null;
    }

    /* get task file
     * @return file
     */
    public function actionGetFile(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isGet){
            $id = $req->get('id');
            $model = SpyFile::findOne(['file_id' => $id]);
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

    /**
     * return data for populate modal form
     */
    public function actionPopulateForm(){
        $app = Yii::$app;
        if($app->request->isAjax){
            $app->response->format = Response::FORMAT_JSON;
            return [
                'clients' => SpyClient::find()->where(['commercial_id' => $app->user->getId()])->select(['client_id','name'])->all(),
                'types' => SpyTaskType::find()->where(['!=', 'name', 'No definido'])->all(),
            ];
        }
        return null;
    }

    /*
     *  upload file previous to create task
     */
    public function actionTempStoreFile(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if ($req->isPost && isset($_FILES['file'])) {
            $path = $app->params['uploadPath']. "/" . "tasks";
            $file = UploadedFile::getInstanceByName('file');
            $originalName = $file->name;
            $generatedName = $app->security->generateRandomString().".".$file->extension;
            $files = $session->get('tempStoreFiles');
            $files==null ? $files = []: null;
            $fileRow = new SpyFile();
            $fileRow->short_description = $originalName;
            $fileRow->path = $path . "/" . $generatedName;
            if($fileRow->save(false)){
                if ($file->saveAs($path . "/" . $generatedName)) {
                    array_push($files, [
                        'file_id' => $fileRow->getPrimaryKey(),
                    ]);
                }
            };
            $session->set('tempStoreFiles',$files);
            return true;
        }
        return null;
    }

    /*
     * create task recovering file from session
     */
    public function actionCreateTask(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $data = $req->post();
            $task = new SpyTask;
            $task->client_id = $data['client'];
            $task->taskT_id = $data['type'];
            $task->subject = $data['subject'];
            $task->description = $data['description'];
            $task->alert = $data['alert'];
            if($task->save(false)){
                $files = $session->get('tempStoreFiles');
                $task->setNewState($task->client);
                if($files!=null){
                    foreach($files as $tempFile){
                        $file_id = $tempFile['file_id'];
                        $file = SpyFile::findOne(['file_id' => $file_id]);
                        if($file instanceof spyFile){
                            $file->task_id = $task->getPrimaryKey();
                            $file->save(false);
                        }
                    }
                    $session->set('tempStoreFiles', null);
                    return true;
                } else {
                    return true;
                }
            }
        }
        return null;
    }

    /*
     * on discard task without finalize, delete temp files on session
     */
    public function actionDiscardTask(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $storedFiles = $session->get('tempStoreFiles');
            if(count($storedFiles)>0){
                foreach($storedFiles as $file_id){
                    $file = SpyFile::findOne(['file_id' => $file_id]);
                    if($file instanceof SpyFile){
                        unlink($file->path);
                        $file->delete();
                    }
                }
            }
            $session->set('tempStoreFiles', null);
            return true;
        }
        return null;
    }

    /*
     * return full task data on open modal task viewer
     */
    public function actionGetFullTask(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $task = SpyTask::findOne(['task_id' => $req->post('id')]);
            if($task instanceof SpyTask){
                $client = $task->client;
                $contact = $client->contactMain;
                $files = $task->files;
                $type = $task->taskType;
                $res->format = Response::FORMAT_JSON;
                return [
                    'id' => $task->task_id,
                    'subject' => $task->subject,
                    'description' => $task->description,
                    'alert' => $task->alert,
                    'type' => $type->name,
                    'typeColor' => $type->color,
                    'client' => $client->name,
                    'contact' => $contact->name,
                    'phone' => $contact->phone,
                    'mail' => $contact->mail,
                    'files' => $files,
                ];
            }
        }
        return null;
    }

    /*
     * create new note
     */
    public function actionNewNote(){
        $app = Yii::$app;
        $req = $app->request;
        $user = $app->user->getId();
        if($req->isAjax){
            $data = $req->post();
            $note = new SpyNote();
            $note->short_description = $data['subject'];
            $note->text = $data['description'];
            $note->commercial_id = $app->user->identity->getId();
            $note->date = $data['date'];
            $user == 2 ? $note->shared = true : $note->shared = false ;
            return $note->save(false);
        }
        return null;
    }

    /*
    * finalize note
    */
    public function actionFinalizeNote(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $data = $req->post();
            $note = SpyNote::findOne(['note_id' => $data['id']]);
            if($note instanceof SpyNote){
                $note->state = false;
                return $note->update(false);
            }
        }
        return null;
    }

}
