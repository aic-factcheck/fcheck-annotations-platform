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

    public function actionIndex($sandbox = false, $oracle = false, $claim = null)
    {
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
