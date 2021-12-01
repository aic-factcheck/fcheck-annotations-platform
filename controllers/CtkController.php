<?php

namespace app\controllers;

use app\helpers\CtkApi;
use app\models\Article;
use app\models\Claim;
use app\models\ClaimKnowledge;
use app\models\ConditionKnowledge;
use app\models\Label;
use app\models\Paragraph;
use app\models\ParagraphKnowledge;
use app\models\ParagraphQueue;
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
        $sample = $this->_ctkApi->getParagraph(ParagraphQueue::dequeue());
        return $this->render('index', ['article' => Article::fromSample($sample), "target" => (int)explode("_", $sample["id"])[1]]);
    }

    public function actionNominate($paragraph, $k_latest=4)
    {
        $paragraph = Paragraph::findOne($paragraph);
        $conf = [
            "k"=>3,"nerlimit"=>256,"prek"=>1024,"niter"=>12,"npts"=>128,"notitles"=>0,"randompts"=>0
        ];
        $dictionary = $this->_ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, $conf);
        ParagraphKnowledge::fromDictionary($paragraph, $dictionary, 5);
        $paragraph->ners = $dictionary['ners'];
        $paragraph->candidate_of = Yii::$app->user->id;
        return $paragraph->save();
    }

    public function actionAugmentKnowledge($claim)
    {
        $claim = Claim::findOne($claim);
        $paragraph = $claim->paragraph0 ?: Paragraph::nearest($claim->tweet0->created_at);
        $dictionary = $this->_ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ['q' => $claim->claim]);
        ClaimKnowledge::fromDictionary($claim, $dictionary);
        $claim->ners = $dictionary['ners'];
        return $claim->save();
    }

    public function actionConditionalKnowledge($label)
    {
        $label = Label::findOne($label);
        $paragraph = $label->claim0->paragraph0;
        $dictionary = $this->_ctkApi->getDictionary($paragraph->article . '_' . $paragraph->rank, ['q' => $label->condition, "nerlimit" => 2, "k" => 2, "npts" => 2, "older" => 1]);
        ConditionKnowledge::fromDictionary($label, $dictionary);
        return $label->save();
    }
}
