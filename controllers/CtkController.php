<?php

namespace app\controllers;

use app\helpers\CtkApi;
use app\models\Article;
use app\models\Claim;
use app\models\ClaimKnowledge;
use app\models\Paragraph;
use app\models\ParagraphKnowledge;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;

class CtkController extends Controller
{
    const API_CONFIG = "nerlimit=2&k=2&npts=2";
    private $_ctkApi;

    public function beforeAction($action)
    {
        $this->_ctkApi = new CtkApi();
        return parent::beforeAction($action);
    }

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

    public function actionIndex()
    {
        $sample = $this->_ctkApi->getSample();
        return $this->render('index', ['article' => Article::fromSample($sample), "target" => (int)explode("_", $sample["id"])[1]]);
    }

    public function actionNominate($paragraph)
    {
        $paragraph = Paragraph::findOne($paragraph);
        $dictionary = $this->_ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank);
        ParagraphKnowledge::fromDictionary($paragraph, $dictionary);
        $paragraph->ners = $dictionary['ners'];
        $paragraph->candidate_of = Yii::$app->user->id;
        return $paragraph->save();
    }

    public function actionAugmentKnowledge($claim)
    {
        $claim = Claim::findOne($claim);
        $paragraph = $claim->paragraph0;
        $dictionary = $this->_ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ['q' => $claim->claim]);
        ClaimKnowledge::fromDictionary($claim, $dictionary);
        $claim->ners = $dictionary['ners'];
        return $claim->save();
    }
}
