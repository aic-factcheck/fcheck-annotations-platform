<?php

namespace app\controllers;

use app\helpers\CtkApi;
use app\models\ClaimForm;
use app\models\MutateForm;
use app\models\Paragraph;
use app\models\ParagraphKnowledge;
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
        if(!$paragraph && random_int(0, 1)) return $this->redirect(['claim/extract-tweet']);
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
            return $this->redirect(['claim/mutate']);
        }
        return $this->render('twitter', ['sandbox' => $sandbox, 'model' => $model]);
    }

    public function actionShowDictionary($sandbox = false, $tweet = false, $k_latest = null, $paragraph = false)
    {
        $ctkApi = new CtkApi();
        if ($paragraph) {
            if ($paragraph == 1) {
                $paragraph = (Paragraph::find()->andWhere(['not', ['candidate_of' => null]])->orderBy(new Expression('rand()'))->one())->id;
            }
            $model = new ClaimForm($sandbox, $paragraph);
            ParagraphKnowledge::deleteAll(['paragraph'=>$model->paragraph->id]);
            $dictionary = $ctkApi->getDictionary($model->paragraph->article . '_' . $model->paragraph->rank, $_GET);
            ParagraphKnowledge::fromDictionary($model->paragraph, $dictionary, $k_latest);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['claim/mutate']);
            }
            return $this->render('annotate', ['sandbox' => $sandbox, 'model' => $model]);

        } else {
            if (!$tweet) {
                $tweet = (Tweet::find()->orderBy(new Expression('rand()'))->one())->id;
            }
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
    }

    public function actionMutate($sandbox = false)
    {
        $model = new MutateForm();
        if ($model->claim == null) {
            Yii::$app->session->addFlash("success", "Obm??ny v??ech Va??ich tvrzen?? byly vypln??ny ???? Nyn?? se m????ete pustit do tvorby dal????ch!");
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
