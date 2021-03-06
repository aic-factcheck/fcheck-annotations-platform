<?php

namespace app\controllers;

use app\models\Claim;
use app\models\ClaimKnowledge;
use app\models\LoginForm;
use app\models\TimeSpent;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionTutorial($t = 0)
    {
        return $this->render('tutorial', ['t' => $t]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionStats()
    {
        return $this->render('stats');
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionStatistics($summer=false)
    {
        return $this->render('sandbox',['summer'=>$summer]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionThesis($summer=false)
    {
        return $this->render('thesis',['summer'=>$summer]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) return $this->goBack();
            Yii::$app->session->addFlash("danger", "Zadan?? SIDOS ID nen?? v syst??mu");
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTimer($time, $route)
    {
        return (new TimeSpent(['user' => Yii::$app->user->id, 'time' => $time, 'route' => $route]))->save();
    }

    public function actionSite()
    {
        return $this->redirect(["/"]);
    }
}
