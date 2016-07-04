<?php

namespace app\controllers;

use app\models\SpyClient;
use app\models\SpyCommercial;
use app\models\SpyContact;
use app\models\SpyPosition;
use app\models\SpyTask;
use app\models\SpyTaskType;
use Yii;
use app\models\ClientList;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class ClientController extends Controller
{
    // let format XXX XX XX XX for phone field for copy & paste from google maps
    private function trimMobile($phone){
        $phoneA = explode(" ",$phone);
        if(count($phoneA)>1){
            $finalPhone = "";
            foreach($phoneA as $part){
                $finalPhone .= $part;
            }
            return $finalPhone;
        } else {
            return $phone;
        }
    }

    /**
     * @return ClientList / ActiveDataProvider => [clients, pagination]
     */
    public function actionIndex()
    {
        $app = Yii::$app;
        $req = $app->request;
        if($app->user->isGuest){
            return $this->goHome();
        }

        $model = new ClientList();
        $params = $req->queryParams;
        if(count($params)>1){
            isset($params['search']) ? $model->search = $params['search'] : $model->search="";
            isset($params['category']) ? $model->category = $params['category'] : $model->category="";
            isset($params['sector']) ? $model->sector = $params['sector'] : $model->sector="";
            isset($params['faculty']) ? $model->faculty = $params['faculty'] : $model->faculty="";
            isset($params['department']) ? $model->department = $params['department'] : $model->department="";
            isset($params['location']) ? $model->location = $params['location'] : $model->location="";
        }
        if($model->load($req->get())){
            return $this->render('index', [
                'model' => $model,
                'clients' => $model->search(),
            ]);
        }

        return $this->render('index', [
            'model' => $model,
            'clients' => $model->search(),
        ]);
    }

    /*
     * @return SpyClient view
     */
    public function actionView(){
        $app = Yii::$app;
        $req = $app->request;
        $client = SpyClient::findOne($req->get('id'));
        return $this->render('view', [
            'client' => $client,
        ]);
    }

    /**
     * @return SpyClient Form
     */
    public function actionCreate(){
        $app = Yii::$app;
        $app->params['uploadPath'] = Yii::$app->basePath . '/files/clients/';
        $path =  $app->params['uploadPath'];
        $req = $app->request;
        $client = new SpyClient();
        $contact = new SpyContact();
        if($app->user->isGuest){
            return $this->goHome();
        }
        if($client->load($req->post()) && $contact->load($req->post())){
            $duplicatedPhone = SpyContact::findOne(['phone' => $contact->phone]);
            if($duplicatedPhone){
                if($duplicatedPhone instanceof SpyContact){
                    $duplicatedClient = $duplicatedPhone->client;
                    $owner = $duplicatedClient->commercial;
                    $app->user->getId() == $duplicatedClient->commercial_id
                        ? $message = "Ya está en tu lista de contactos a nombre de ".$duplicatedClient->name
                        : $message ="Pertenece a ".$owner->name;
                    $contact->addError('phone',$message);
                    return $this->render('create',[
                        'client' => $client,
                        'contact' => $contact,
                    ]);
                }
            } else {
                $client->category_id==null ? $client->category_id=1 : null;
                $client->sector_id==null ? $client->sector_id=1 : null;
                $client->commercial_id = $app->user->getId();
                if($client->save(false)){
                    $image = UploadedFile::getInstance($client, 'photo');
                    if(strlen($image) > 0){
                        $ext = end(explode(".", $image->name));
                        $generatedFileName = $client->getPrimaryKey().".{$ext}";
                        $image->saveAs($path . $generatedFileName);
                        $client->photo =  '../files/clients/'.$generatedFileName;
                        $client->save(false);
                    }
                    $contact->client_id = $client->getPrimaryKey();
                    $contact->phone = $this->trimMobile($contact->phone);
                    $contact->position_id==null ? $contact->position_id = 1: null;
                    $contact->main = true;
                    $contact->save(false);
                }
                return  $this->redirect('?r=client/view&id='.$client->getPrimaryKey());
            }
        }
        return $this->render('create',[
            'client' => $client,
            'contact' => $contact,
        ]);
    }

    /*
     * @return SpyClient updateView
     */
    public function actionUpdate($id){
        $app = Yii::$app;
        $req = $app->request;
        $app->params['uploadPath'] = Yii::$app->basePath . '/files/clients/';
        $path =  $app->params['uploadPath'];
        $client = $this->findModel($id);
        if($client instanceof SpyClient){
            $contact = $client->contactMain;
            if($client->load($req->post()) && $contact->load($req->post()) && $contact instanceof SpyContact){
                $contact->phone = $this->trimMobile($contact->phone);
                $contact->position==null ? $contact->position_id = 1 : null;
                if($contact->save(false)){
                    $image = UploadedFile::getInstance($client, 'photo');
                    if(strlen($image) > 0){
                        $ext = end(explode(".", $image->name));
                        $generatedFileName = $client->getPrimaryKey().".{$ext}";
                        $image->saveAs($path . $generatedFileName);
                        $client->photo =  '../files/clients/'.$generatedFileName;
                    }
                    $client->category_id==null ? $client->category_id=1 : null;
                    $client->sector_id==null ? $client->sector_id=1 : null;
                    if($client->save(false)){
                        return $this->redirect('?r=client/view&id='.$client->getPrimaryKey());
                    }

                }
            }
        }
        return $this->render('update',[
            'client' => $client,
            'contact' => $contact,
        ]);
    }


    /*
     * save client to future route
     * @return boolean confirming action
     */
    public function actionAddToRoute(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $routes = $session->get('routes');
            array_push($routes, $req->post('id'));
            $session->set('routes', $routes);
            return true;
        }
        return null;
    }

    /*
     * save client to future route
     * @return boolean confirming action
     */
    public function actionDeleteOfRoute(){
        $app = Yii::$app;
        $req = $app->request;
        $session = $app->session;
        if($req->isAjax){
            $data = $req->post();
            $routes = $session->get('routes');
            $key = array_search($data['id'], $routes);
            unset($routes[$key]);
            $session->set('routes', $routes);
            return true;
        }
        return null;
    }

    /**
     * return Client Massive view
     */
    public function actionMassive(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        return $this->render('massive');
    }

    /**
     * return true || duplicated client data
     */
    public  function actionMassiveCreation(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        $user = $app->user->identity->getId();
        if($req->isAjax){
            $res->format = Response::FORMAT_JSON;
            $data = $req->post();
            $contact = SpyContact::findOne(['phone' => $data['phone']]);
            if($contact){
                if($contact instanceof SpyContact){
                    $client = $contact->client;
                    if($client instanceof SpyClient){
                        if($client->commercial->commercial_id == $user){
                            return [
                                'owner' => true,
                                'commercial' => $client->commercial->name,
                                'client' => [
                                    'id' => $client->client_id,
                                    'name' => $client->name,
                                    'phone' => $contact->phone
                                ],
                            ];
                        } else {
                            return [
                                'owner' => false,
                                'commercial' => $client->commercial->name,
                                'client' => [
                                    'id' => $client->client_id,
                                    'name' => $client->name,
                                    'phone' => $contact->phone
                                ],
                            ];
                        }
                    }
                }
            } else {
                $client = new SpyClient();
                $contact = new SpyContact();
                $client->name = $data['name'];
                $client->commercial_id = $user;
                $client->sector_id = $data['sector'];
                $client->category_id = 1;
                if($client->save(false)){
                    $contact->client_id = $client->getPrimaryKey();
                    $contact->name = $data['contact'];
                    $contact->phone = $data['phone'];
                    $contact->mail = $data['mail'];
                    $contact->main = 1;
                return $contact->save(false) ? true : false;
                }
            }
        }
        return null;
    }


    /**
     * add seconds contact to client
     * @return SpyContact id already exists
     */
    public function actionCreateContact(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $data = $req->post();
            $contactExist = SpyContact::findOne(['phone' => $data['phone']]);
            if(!$contactExist){
                $contact = new SpyContact();
                $contact->client_id = $data['client'];
                $contact->name = $data['name'];
                $contact->phone = $this->trimMobile($data['phone']);
                $contact->mail = $data['mail'];
                $contact->position_id =$data['position'];
                $contact->main = 0;
                if($contact->save(false)){
                    return true;
                }
            } else {
                $res->format = Response::FORMAT_JSON;
                if($contactExist instanceof SpyContact){
                    $client = $contactExist->client;
                    $commercial = $client->commercial;
                    if($client->commercial_id == $app->user->getId()){
                        return [
                            'owner' => true,
                            'client' => $client->name,
                            'phone' => $contactExist->phone,
                        ];
                    } else {
                        return [
                            'owner' => false,
                            'commercial' => $commercial->name,
                            'client' => $contactExist->client->name,
                            'phone' => $contactExist->phone,
                        ];
                    }
                }
            }
        }
        return null;
    }

    /*
     * get contact positions
     * @return SpyPosition all
     */
    public function actionGetContactPositions(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $res->format = Response::FORMAT_JSON;
            $positionList = [];
            foreach(SpyPosition::find()->orderBy('name ASC')->where(['!=', 'name', 'No definido'])->all() as $position){
                if($position instanceof SpyPosition){
                    array_push($positionList, [
                        'position_id' => $position->position_id,
                        'name' => $position->name,

                    ]);
                }
            }
            return $positionList;

        }
        return null;
    }

    /*
     * add SpyPosition
     */
    public function actionAddPosition(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $data = $req->post();
            $exist = SpyPosition::find()->where(['LIKE', 'name', $data['position']])->all();
            $res->format = Response::FORMAT_JSON;
            if($exist){
                return $exist[0]->name;
            } else {
                $position = new SpyPosition();
                $position->name = $data['position'];
                return $position->save(false);
            }
        }
        return null;
    }

    /*
     * set contact selected as main
     */
    public function actionSetContactMain(){
        $req = Yii::$app->request;
        $res = Yii::$app->response;
        if($req->isAjax){
            $res->format = Response::FORMAT_JSON;
            $nextMain = SpyContact::findOne($req->post('contact'));
            if($nextMain instanceof SpyContact){
                $currentMain = $nextMain->client->contactMain;
                if($currentMain instanceof SpyContact){
                    $currentMain->main = false;
                    if($currentMain->save(false)){
                        $nextMain->main = true;
                        return $nextMain->save(false);
                    }
                }

            }
        }
        return null;
    }

    /*
     *@return SpyClient formatted statistics
     */
    public function actionGetStatistics(){
        $app = Yii::$app;
        $req = $app->request;
        $res = $app->response;
        if($req->isAjax){
            $data = [];
            $colors = [];
            $TOTAL = SpyTask::find()
                ->joinWith('taskType')
                ->where(['client_id' => $req->post('id')])
                ->count();
            $connection = Yii::$app->getDb();
            $counters = 'SELECT spy_taskt.name as label, count(task_id) as value FROM spy_taskt
                        JOIN spy_task USING (taskT_id)
                        JOIN spy_client USING (client_id)
                        WHERE client_id = '.$req->post('id').'
                        GROUP BY taskT_id';
            $cData = $connection->createCommand($counters)->queryAll();
            foreach ($cData as $type) {
                $color = SpyTaskType::find()->where(['name' => $type['label']])->select('color')->all();
                array_push($colors, $color[0]->color);
                array_push($data, [
                    'label' => $type['label'],
                    'value' => $type['value'],
                    'formatted' =>  round($type['value']*100/$TOTAL, 1, PHP_ROUND_HALF_UP)."% - ". $type['value']." tareas",
                ]);
            }
            $res->format = Response::FORMAT_JSON;
            return [
                'types' => $data,
                'colors' => $colors
            ];
        }
        return null;
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = SpyClient::findOne(['client_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('El cliente solicitado no existe');
        }
    }
}
