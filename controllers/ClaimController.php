<?php

namespace app\controllers;

use app\models\ClaimForm;
use app\models\MutateForm;
use Yii;
use yii\filters\AccessControl;
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

    public function actionMutate($sandbox = false)
    {
        $model = new MutateForm();
        if ($model->claim == null){
            Yii::$app->session->addFlash("success", "Mutace všech Vašich tvrzení byly vyplněny 😊 Nyní se můžete pustit do tvorby dalších!");
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
