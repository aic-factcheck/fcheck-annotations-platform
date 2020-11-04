<?php

namespace app\controllers;

use app\models\Claim;
use app\models\LabelForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;

class LabelController extends Controller
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
        if ($claim == null) {
            return $this->redirect([
                'index',
                'sandbox' => $sandbox,
                'oracle' => $oracle,
                'claim' =>
                    Claim::find()
                        ->where(['labelled' => $oracle, 'sandbox' => $sandbox])
                        ->orderBy(new Expression('rand()'))
                        ->one()
                        ->getPrimaryKey()
            ]);
        }

        $model = new LabelForm($sandbox, $oracle, Claim::findOne($claim));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Anotace úspěšně uložena.");
            return $this->redirect(['index', 'sandbox' => $sandbox, 'oracle' => $oracle,]);

        }
        return $this->render('index', ['model' => $model]);
    }

}
