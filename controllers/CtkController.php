<?php

namespace app\controllers;

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
        $client = new Client();
        $sample = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://localhost:8601/sample')
            ->send()
            ->getData();
        return $this->render('index', ['article' => Article::fromSample($sample), "target" => (int)explode("_", $sample["id"])[1]]);
    }

    public function actionNominate($paragraph)
    {
        $paragraph = Paragraph::findOne($paragraph);
        $dictionary = (new Client())->createRequest()
            ->setMethod('GET')
            ->setUrl('http://localhost:8601/dictionary/' . $paragraph->article . '_' . $paragraph->rank)
            ->send()
            ->getData();
        ParagraphKnowledge::fromDictionary($paragraph, $dictionary);
        $paragraph->ners = $dictionary['ners'];
        $paragraph->candidate_of = Yii::$app->user->id;
        return $paragraph->save();
    }

    public function actionAugmentKnowledge($claim)
    {
        $claim = Claim::findOne($claim);
        $q = urlencode($claim->claim);
        $paragraph = $claim->paragraph0;
        $dictionary = (new Client())->createRequest()
            ->setMethod('GET')
            ->setUrl("http://localhost:8601/dictionary/{$paragraph->article}_{$paragraph->rank}?q=$q")
            ->send()
            ->getData();
        ClaimKnowledge::fromDictionary($claim, $dictionary);
        $claim->ners = $dictionary['ners'];
        return $claim->save();
    }
}
