<?php

namespace app\controllers;

use app\models\Claim;
use app\models\LabelForm;
use yii\httpclient\Client;
use SQLite3;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;

class CandidateController extends Controller
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

    public function actionIndex($add = null)
    {
        if($add != null){
            $add = json_decode($add,true);
            Yii::$app->params['live'][] = $add;
            $fp = fopen('../config/datasets/live.json', 'w');
            fwrite($fp, json_encode(Yii::$app->params['live'],JSON_UNESCAPED_UNICODE));
            fclose($fp);
            Yii::$app->params['entities'][$add['entity']] = $add['entity_sentences'];
            $fp = fopen('../config/datasets/entities.json', 'w');
            fwrite($fp, json_encode(Yii::$app->params['entities'],JSON_UNESCAPED_UNICODE));
            fclose($fp);
            Yii::$app->session->addFlash("success", "Kandidátní věta byla úspěšně přidána");
            return $this->redirect(['index']);
        }
        $db = new SQLite3('../ctk.db');
        $res = $db->query('select * from documents where rowid > (abs(random()) % (select (select max(rowid) from documents)+1)) LIMIT 30');
        $data = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $this->render('index', ['data' => $data]);
    }

    public function actionAlt($add = null)
    {
        $client = new Client();
        if($add != null){
            $add = json_decode($add,true);
            $add['dictionary'] = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://localhost:8601/dictionary/'.$add["id"])
                ->send()
                ->getData();
            Yii::$app->params['live'][] = $add;
            $fp = fopen('../config/datasets/live.json', 'w');
            fwrite($fp, json_encode(Yii::$app->params['live'],JSON_UNESCAPED_UNICODE));
            fclose($fp);
            Yii::$app->params['entities'][$add['entity']] = $add['entity_sentences'];
            $fp = fopen('../config/datasets/entities.json', 'w');
            fwrite($fp, json_encode(Yii::$app->params['entities'],JSON_UNESCAPED_UNICODE));
            fclose($fp);
            Yii::$app->session->addFlash("success", "Kandidátní věta byla úspěšně přidána");
            return $this->redirect(['alt']);
        }

        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://localhost:8601/sample')
            ->send();
        return $this->render('alt', ['data' => $response->getData()]);
    }


}
