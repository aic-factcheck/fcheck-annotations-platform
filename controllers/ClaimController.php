<?php

namespace app\controllers;

use app\helpers\CtkApi;
use app\models\ClaimForm;
use app\models\MutateForm;
use app\models\Paragraph;
use app\models\Tweet;
use app\models\TweetKnowledge;
use app\models\TwitterForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ClaimController extends Controller
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
        ];
    }


    public function actionAnnotate($sandbox = false, $paragraph = false)
    {
        $model = new ClaimForm($sandbox, $paragraph);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['claim/mutate']);
        }
        return $this->render('annotate', ['sandbox' => $sandbox, 'model' => $model]);
    }

    public function actionExtractTweet($sandbox = false, $tweet = false)
    {
        $model = new TwitterForm($sandbox, $tweet);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['claim/extract-tweet']);
        }
        return $this->render('twitter', ['sandbox' => $sandbox, 'model' => $model]);
    }

    public function actionShowDictionary($sandbox = false, $tweet = false, $k_latest=null)
    {
        if (!$tweet) {
            $tweet = (Tweet::find()->orderBy(new Expression('rand()'))->one())->id;
        }
        $ctkApi = new CtkApi();
        $model = new TwitterForm($sandbox, $tweet);
        TweetKnowledge::deleteAll(['tweet' => $model->tweet->id]);
        $paragraph = Paragraph::nearest($model->tweet->created_at);
        $dictionary = $ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ArrayHelper::merge(['q' => $model->tweet->text], $_GET));
        TweetKnowledge::fromDictionary($model->tweet, $dictionary, $k_latest);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['claim/extract-tweet']);
        }
        return $this->render('twitter', ['sandbox' => true, 'model' => $model]);
    }

    public function actionMutate($sandbox = false)
    {
        $model = new MutateForm();
        if ($model->claim == null) {
            Yii::$app->session->addFlash("success", "Obměny všech Vašich tvrzení byly vyplněny 😊 Nyní se můžete pustit do tvorby dalších!");
            return $this->redirect(['claim/annotate', 'sandbox' => $sandbox]);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['claim/mutate', 'sandbox' => $sandbox]);
        }
        return $this->render('mutate', ['model' => $model, 'sandbox' => $sandbox]);
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionTutorial()
    {
        return $this->render('tutorial');
    }

}
