<?php

namespace app\controllers;

use app\models\Claim;
use app\models\Label;
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
            $traversed = [];
            do {
                $claim = Claim::find()
                    ->where(['sandbox' => $sandbox])
                    ->andWhere(['is not', 'mutation_type', null])
                    ->andWhere(['not in', 'id', $traversed])
                    ->andWhere([$oracle ? '=' : '<>', 'user', Yii::$app->user->id])
                    ->orderBy(new Expression('rand()'))
                    ->one();
                if ($claim == null) {
                    Yii::$app->session->addFlash("info", "V současnosti v sekci <strong>" . ($oracle ? 'vlastní' : 'cizí') . " tvrzení</strong> není co anotovat. 😟 " .
                        ($oracle ? "Výroky nejprve vytvořte v Ú1!" : "Počkejte než ostatní vytvoří výroky v Ú1."));
                    return $this->goHome();
                }
                $traversed[] = $claim = $claim->getPrimaryKey();
            } while (Label::find()->where(['claim' => $claim, 'user' => Yii::$app->user->id])->exists());

            return $this->redirect([
                'index',
                'sandbox' => $sandbox,
                'oracle' => $oracle,
                'claim' => $claim
            ]);
        }

        $model = new LabelForm($sandbox, $oracle, Claim::findOne($claim));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Anotace úspěšně uložena.");
            return $this->redirect(['index', 'sandbox' => $sandbox, 'oracle' => $oracle,]);

        }
        return $this->render('index', ['model' => $model, 'oracle' => $oracle]);
    }

}
