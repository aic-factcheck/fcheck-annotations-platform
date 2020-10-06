<?php

namespace app\controllers;

use app\models\ClaimForm;
use Yii;
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

    public function actionIndex($sandbox = false, $oracle = false)
    {
        $model = new ClaimForm($sandbox);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['claim/mutate']);
        }
        return $this->render('annotate', ['sandbox' => $sandbox, 'model' => $model]);
    }

}
