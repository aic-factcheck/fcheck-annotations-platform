<?php

namespace app\controllers;

use app\models\Claim;
use app\models\LabelForm;
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
        $lines = [];
        foreach ($data as $par) {
            $par_lines = preg_split('/\d+\t/', $par['lines']);
            foreach ($par_lines as $line) {
                if(strlen($line)>1){
                    $lines[]=$line;
                }
            }
        }
        return $this->render('index', ['data' => $data]);
    }

}
