<?php

namespace app\controllers;

use app\models\SpyFile;
use app\models\SpyGuide;
use app\models\SpyNote;
use Yii;
use app\models\SpyClient;
use app\models\SpyContact;
use app\models\SpyTask;
use app\models\SpyTaskType;
use app\models\ClientsParser;
use app\models\SpyCommercial;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/files';

class AdminController extends Controller
{
    public $layout = "_blank";

    public function beforeAction($action){
        if ($action->id == 'file-upload' || $action->id == 'temp-store-file') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    public function actionIndex(){
        $app = Yii::$app;
        $user = $app->user;
        if($user->isGuest || $user->id != 2){
            return $this->goHome();
        }
        return $this->render('index', [
            'commercials' =>  SpyCommercial::find()
                ->select(['commercial_id', 'name', 'lastname'])
                ->where(['!=', 'commercial_id', 2])
   /*             ->andWhere(['!=', 'commercial_id', 1])*/
                ->all(),
            'totalCommercials' => SpyCommercial::find()->where(['!=', 'commercial_id', 2])->count(),
            'totalTasks' => SpyTask::find()
                ->joinWith('client')
                ->where(['!=', 'commercial_id', 2])
                ->andWhere(['!=', 'commercial_id', 1])
                ->count(),
            'totalTaskToday' => SpyTask::find()
                ->joinWith('client')
                ->where(['!=', 'commercial_id', 2])
                ->andWhere(['!=', 'commercial_id', 1])
                ->andWhere(['between','spy_task.date',date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
                ->count(),
        ]);
    }

    /*
     * create commercial
     */
    public function actionCreateCommercial(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $data = $req->post();
            $commercial = new SpyCommercial();
            $commercial->name = $data['name'];
            $commercial->lastname = $data['lastName'];
            $commercial->email = $data['email'];
            $commercial->password = $data['password'];
            return $commercial->save();
        }
        return null;
    }

    /*
    * parse clients
    */
    public function actionParse(){
        $app = Yii::$app;
        $data = $app->request->post();
        $user = $app->user;
        $model = new ClientsParser();
        if ((!$user->isGuest) && ($user->id == 2)) {
            if($model->load($data) && $model->validate()){
                $clients = $model->parseClient();
                foreach($clients as $cl){
                    $client = new SpyClient();
                    $client->name = $cl['client_name'];
                    $client->category_id = $cl['category'];
                    $client->sector_id = $cl['sector'];
                    $client->address = $cl['address'];
                    $client->postal_code = $cl['cp'];
                    $client->city = $cl['city'];
                    $client->province = "GRANADA";
                    $cl['comment']!=null ? $client->comment = $cl['comment'] : $client->comment = "";
                    $client->commercial_id = $model->commercial;
                    if($client->save(false)){
                        $mainContact = new SpyContact();
                        $mainContact->name = $cl['contact_name'];
                        $mainContact->phone = $cl['contact_phone'];
                        $mainContact->mail = $cl['contact_mail'];
                        $mainContact->position_id = 1;
                        $mainContact->main = true;
                        $mainContact->client_id = $client->getPrimaryKey();
                        if($mainContact->save(false)){
                            if(!empty($cl['second_contact_phone'])){
                                $secondContact = new SpyContact();
                                $secondContact->name = $cl['second_contact_name'];
                                $secondContact->phone = $cl['second_contact_phone'];
                                $secondContact->mail = $cl['second_contact_mail'];
                                $secondContact->position_id = 1;
                                $secondContact->client_id = $client->getPrimaryKey();
                                $secondContact->save(false);
                            }
                        }
                    }
                };
                return $this->render('parse',[
                    'model' => $model,
                    'data' => $clients,
                ]);
            }

            return $this->render('parse',[
                'model' => $model,
            ]);
        }
        return $this->goHome();
    }

    /*
     * render calendar with commercial selector
     */
    public function actionCalendar(){
        $app = Yii::$app;
        if($app->user->id == 2){
            return $this->render('calendar', [
                'commercials' =>  SpyCommercial::find()
                    ->select(['commercial_id', 'name', 'lastname'])
                    ->where(['!=', 'commercial_id', 2])
                   /* ->andWhere(['!=', 'commercial_id', 1])*/
                    ->all(),
                'types' => SpyTaskType::find()->all(),
            ]);
        }
        return $this->goHome();
    }

    /*
     * return commercial´s task selected
     */
    public function actionGetCommercialTasks(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $user = $app->user->id;
        if($req->isAjax && $user == 2){;
            $commercialTasks = [];
            $tasksUp = SpyTask::find()
                ->joinWith('client')
                ->where(['spy_task.state' => 1])
                ->andWhere(['spy_client.commercial_id' => $req->post('commercial')])
                ->all();
            foreach($tasksUp as $task){
                if($task instanceof SpyTask){
                    $type = $task->taskType;
                    array_push($commercialTasks, [
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
            $res->format = Response::FORMAT_JSON;
            return [
                'events' =>  $commercialTasks,
            ];
        }
        return null;
    }

    /*
     * render guides view
     */
    public function actionGuides(){
        $app = Yii::$app;
        $user = $app->user;
        if(!$user->isGuest && $user->id == 2){
            return $this->render('guides', [
               'guides' => SpyGuide::find()->all(),
            ]);
        }
        return $this->goHome();
    }

    /*
     * get statistics and tasks for selected commercial
     */
    public function actionGetCommercialFullData(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax && $app->user->id == 2){
            /* arrays for all data */
            $donutToday = [];
            $colorToday = [];
            $donutWeek = [];
            $colorWeek = [];
            $donutMonth = [];
            $colorMonth = [];
            /* sql connector */
            $commercial = $req->post('id');
            /* TODAY */
            $day_start = date('Y-m-d 00:00:00');
            $day_end =  date('Y-m-d 23:59:59');
            $today = $app->getDb()->createCommand(
                "SELECT spy_taskt.name as label, count(task_id) as value
                FROM spy_taskt
                JOIN spy_task USING (taskT_id)
                JOIN spy_client USING (client_id)
                WHERE commercial_id = ".$commercial."
                AND spy_task.state = true
                AND spy_task.date BETWEEN '".$day_start."' AND '".$day_end."'
                GROUP BY spy_task.taskT_id"
            )->queryAll();
            foreach ($today as $type) {
                $color = SpyTaskType::find()->where(['name' => $type['label']])->select('color')->all();
                array_push($colorToday, $color[0]->color);
                array_push($donutToday, [
                    'label' => $type['label'],
                    'value' => $type['value'],
                    'formatted' => $type['value'],
                ]);
            }
            /* WEEK */
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(7-$day).' days'));
            $week = $app->getDb()->createCommand(
                "SELECT spy_taskt.name as label, count(task_id) as value
                FROM spy_taskt
                JOIN spy_task USING (taskT_id)
                JOIN spy_client USING (client_id)
                WHERE commercial_id = ".$commercial."
                AND spy_task.state = true
                AND spy_task.date BETWEEN '".$week_start."' AND '".$week_end."'
                GROUP BY spy_task.taskT_id"
            )->queryAll();
            foreach ($week as $type) {
                $color = SpyTaskType::find()->where(['name' => $type['label']])->select('color')->all();
                array_push($colorWeek, $color[0]->color);
                array_push($donutWeek, [
                    'label' => $type['label'],
                    'value' => $type['value'],
                    'formatted' => $type['value'],
                ]);
            }
            /* MONTH */
            $month_start = date('Y-m-d', strtotime(date('Y-m-1')));
            $month_end = date("Y-m-t");
            $month = $app->getDb()->createCommand(
                "SELECT spy_taskt.name as label, count(task_id) as value
                FROM spy_taskt
                JOIN spy_task USING (taskT_id)
                JOIN spy_client USING (client_id)
                WHERE commercial_id = ".$commercial."
                AND spy_task.state = true
                AND spy_task.date BETWEEN '".$month_start."' AND '".$month_end."'
                GROUP BY spy_task.taskT_id"
            )->queryAll();
            foreach ($month as $type) {
                $color = SpyTaskType::find()->where(['name' => $type['label']])->select('color')->all();
                array_push($colorMonth, $color[0]->color);
                array_push($donutMonth, [
                    'label' => $type['label'],
                    'value' => $type['value'],
                    'formatted' => $type['value'],
                ]);
            }
            /* tasks */
            $openedList =[];
            $openedTasks = SpyTask::find()
                ->joinWith('client')
                ->where(['spy_client.commercial_id' => $req->post('id')])
                ->andWhere(['spy_task.state' => 1])
                ->orderBy('spy_task.alert ASC')
                ->limit(15)
                ->all();
            foreach($openedTasks as $task){
                if($task instanceof SpyTask){
                    $client = $task->client;
                    $type = $task->taskType;
                    array_push($openedList, [
                        'client' => $client->name,
                        'type' => $type->name,
                        'color' => $type->color,
                        'subject' => $task->subject,
                        'description' => $task->description,
                        'alert' => $task->alert,
                    ]);
                }
            }
            $closedLIst =[];
            $closedTasks = SpyTask::find()
                ->joinWith('client')
                ->where(['spy_client.commercial_id' => $req->post('id')])
                ->andWhere(['spy_task.state' => 0])
                ->orderBy('spy_task.date DESC')
                ->limit(15)
                ->all();
            foreach($closedTasks as $task){
                if($task instanceof SpyTask){
                    $client = $task->client;
                    $type = $task->taskType;
                    array_push($closedLIst, [
                        'client' => $client->name,
                        'type' => $type->name,
                        'color' => $type->color,
                        'subject' => $task->subject,
                        'description' => $task->description,
                        'alert' => $task->alert,
                        'report' => $task->report,
                    ]);
                }
            }
            $res->format = Response::FORMAT_JSON;
            return [
                'today' => [
                    'types' => $donutToday,
                    'colors' => $colorToday
                ],
                'week' => [
                    'types' => $donutWeek,
                    'colors' => $colorWeek
                ],
                'month' => [
                    'types' => $donutMonth,
                    'colors' => $colorMonth
                ],
                'taskList' => [
                    'opened' => $openedList,
                    'closed' => $closedLIst,
                ]
            ];
        }
        return null;
    }

    /*
     * get individual statistics
     */
    public function actionGetStatisticData(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax and $app->user->id == 2){
            $period = $req->post('period');
            $state = $req->post('type');
            $commercial = $req->post('commercial');
            $QUERY = "SELECT spy_taskt.name as label, count(task_id) as value
                      FROM spy_taskt
                      JOIN spy_task USING (taskT_id)
                      JOIN spy_client USING (client_id)
                      WHERE commercial_id = ".$commercial;
            switch($period){
                case "month":
                    $start = date('Y-m-d', strtotime(date('Y-m-1')));
                    $end = date("Y-m-t");
                    break;
                case "week":
                    $day = date('w');
                    $start = date('Y-m-d', strtotime('-'.$day.' days'));
                    $end = date('Y-m-d', strtotime('+'.(7-$day).' days'));
                    break;
                default:
                    $start = date('Y-m-d 00:00:00');
                    $end = date('Y-m-d 23:59:59');
            }
            switch($state){
                case "finalized":
                    $QUERY .= " AND spy_task.date BETWEEN '".$start."' AND '".$end."'";
                    $QUERY .= " AND spy_task.state = 0";
                    break;
                case "opened":
                    $QUERY .= " AND spy_task.alert BETWEEN '".$start."' AND '".$end."'";
                    $QUERY .= " AND spy_task.state = 1";
                    break;
                default:
                    $QUERY .= " AND spy_task.date BETWEEN '".$start."' AND '".$end."'";
                    $QUERY .= " AND spy_task.state = 1";
            }
            $QUERY .= " GROUP BY spy_task.taskT_id";
            $data = $app->getDb()->createCommand($QUERY)->queryAll();
            $statistics = [];
            $colors = [];
            foreach ($data as $type) {
                $color = SpyTaskType::find()->where(['name' => $type['label']])->select('color')->all();
                array_push($colors, $color[0]->color);
                array_push($statistics, [
                    'label' => $type['label'],
                    'value' => $type['value'],
                    'formatted' => $type['value'],
                ]);
            }
            $res->format = Response::FORMAT_JSON;
            return [
                'stats' => $statistics,
                'colors' => $colors,
            ];
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
            $path = $app->params['uploadPath']. "/" . "guides";
            $guideId = $req->get('id');
            $file = UploadedFile::getInstanceByName('file');
            $originalName = $file->name;
            $generatedName = $app->security->generateRandomString().".".$file->extension;
            $_file = new SpyFile();
            $_file->short_description = $originalName;
            $_file->path = $path . "/" . $generatedName;
            $_file->guide_id = $guideId;
            if($_file->save()){
                if ($file->saveAs($path . "/" . $generatedName)) {
                    return true;
                }
            }
        }
        return null;
    }

    /*
     * update guide description
     */
    public function actionUpdateGuide(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id == 2){
            $guide = SpyGuide::findOne(['guide_id' => $req->post('id')]);
            if($guide instanceof SpyGuide){
                $guide->text = $req->post('text');
                return $guide->save(false);
            }
        }
        return null;
    }

    /*
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

    /*
     * remove file from guide
    * @return file
    */
    public function actionRemoveFile(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id == 2){
            $file = SpyFile::findOne(['file_id' => $req->post('id')]);
            if($file instanceof SpyFile){
                unlink($file->path);
                return $file->delete();
            }
        }
        return null;
    }

    /*
     * create guide getting files from session
     */
    public function actionCreateGuide(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $data = $req->post();
            $guide = new SpyGuide();
            $guide->short_description = $data['short_d'];
            $guide->text = $data['text'];
            if($guide->save(false)){
                $files = $session->get('tempStoreFiles');
                if($files!=null){
                    foreach($files as $tempFile){
                        $file_id = $tempFile['file_id'];
                        $file = SpyFile::findOne(['file_id' => $file_id]);
                        if($file instanceof spyFile){
                            $file->guide_id = $guide->getPrimaryKey();
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
    *  upload file previous to create guide
    */
    public function actionTempStoreFile(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if ($req->isPost && isset($_FILES['file'])) {
            $path = $app->params['uploadPath']. "/" . "guides";
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
    * on discard guide, delete temp files on session
    */
    public function actionDiscardGuide(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $storedFiles = $session->get('tempStoreFiles');
            if($storedFiles!=null){
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
        }
        return null;
    }

    /*
    * remove guide
    */
    public function actionDeleteGuide(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id==2){
            $guide = SpyGuide::findOne(['guide_id' => $req->post('id')]);
            if($guide instanceof SpyGuide){
                foreach($guide->files as $file){
                    if($file instanceof SpyFile){
                        unlink($file->path);
                        $file->delete();
                    }
                }
            }
            return $guide->delete();
        }
        return null;
    }

    /*
     * render notes view
     */
    public function actionNotes(){
        $app = Yii::$app;
        $user = $app->user;
        if($user->isGuest){
            return $this->goHome();
        }
        if($user->id==2){
            return $this->render('notes', [
               'notes' => SpyNote::find()
                   ->where(['commercial_id' => 2])
                   ->andWhere(['state' => 1])
                ->all(),

            ]);
        }
        return null;
    }

    /*
     * finalize note
     */
    public function actionFinalizeNote(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id==2){
            $note = SpyNote::findOne(['note_id' => $req->post('id')]);
            if($note instanceof SpyNote) {
                $note->state = 0;
                return $note->save(false);
            }

        }
        return null;
    }

    /*
     * update note
     */
    public function actionUpdateNote(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id==2){
            $note = SpyNote::findOne(['note_id' => $req->post('id')]);
            if($note instanceof SpyNote){
                $note->text = $req->post('text');
                return  $note->save(false);
            }
        }
        return null;
    }

    /*
     * delete note
     */
    public function actionDeleteNote(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax && $app->user->id==2){
            $note = SpyNote::findOne(['note_id' => $req->post('id')]);
            if($note instanceof SpyNote)
                return $note->delete();
        }
        return null;
    }

    /*
     *  return list of commercials
     */
    public function actionGetCommercialsList(){
        $app = Yii::$app;
        $req = $app->request;
        if($req->isAjax){
            $commercials = SpyCommercial::find()
                ->where(['!=', 'commercial_id', 1])
                ->andWhere(['!=', 'commercial_id', 2])
                ->all();
            $app->response->format = Response::FORMAT_JSON;
            return $commercials;
        }
        return null;
    }
}
