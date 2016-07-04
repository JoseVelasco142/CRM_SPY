<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SpyTask;


class SiteController extends Controller
{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            $this->layout = "index";
            $model = new LoginForm();
            return $this->render('index', [
                'model' => $model,
            ]);
        }
        if(Yii::$app->user->getId()!=2){
            $allTasks = SpyTask::find()
                ->joinWith('client')
                ->where(['commercial_id' => Yii::$app->user->getId()])
                ->andWhere(['spy_task.state' => true])
                ->orderBy('alert ASC')
                ->all();
            $this->layout = "main";
            return $this->render('home',[
                'allTasks' =>$allTasks
            ]);
        } else {
            return $this->redirect('?r=admin/index');
        }

    }


    public function actionLogin(){
        $app = Yii::$app;
        $user = $app->user;
        $data = $app->request->post();

        if (!$user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load($data) && $model->login()) {
            $session = $app->session;
            $session->open();
            $session->set('routes', []);
            $this->goBack();
        }
        $this->layout = "index";
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionLogout(){
        $app = Yii::$app;
        $app->user->logout();
        $app->session->destroy();
        return $this->goHome();
    }

}
