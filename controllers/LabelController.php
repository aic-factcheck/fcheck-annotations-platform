<?php

namespace app\controllers;

use app\models\Claim;
use app\models\Evidence;
use app\models\Label;
use app\models\LabelForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

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
            } while (Label::find()
                ->where(['claim' => $claim, 'user' => Yii::$app->user->id])
                ->orWhere(['flag' => 1, 'claim' => $claim])
                ->exists());

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

    public function actionJsonl()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $labels = [];
        foreach (Label::find()->all() as $label) {
            if ($label->label != null) {
                if (array_key_exists($label->claim, $labels) && ($labels[$label->claim] == null || $labels[$label->claim]->label != $label->label)) {
                    $labels[$label->claim] = null;
                } else {
                    $labels[$label->claim] = $label;
                    if ($label->condition != null) {
                        $label->label = "NOT ENOUGH INFO";
                    }
                }
            }
        }
        $response = "";
        foreach ($labels as $id => $label) {
            if ($label !== null) {
                //{"id": 75397, "verifiable": "VERIFIABLE", "label": "SUPPORTS", "claim": "Nikolaj Costny.", "evidence": [[[92206, 104971, "Nikolaj_Coster-Waldau", 7], [92206, 104971, "Fox_Broadcasting_Company", 0]]]}
                $evidences = [];
                foreach ($label->evidences as $evidence) {
                    if (!array_key_exists($evidence->group, $evidences)) {
                        $evidences[$evidence->group] = [];
                    }
                    $evidences[$evidence->group][] = [
                        $label->id, ($evidence->group * 1000000 + $label->id), $label->claim0->paragraph0->getCtkId(), 0];
                }
                $response .= json_encode([
                        "id" => $id,
                        "verifiable" => ($label->label == "NOT ENOUGH INFO" ? "NOT VERIFIABLE" : "VERIFIABLE"),
                        "label" => $label->label,
                        "claim" => $label->claim0->claim,
                        "evidence" => $evidences
                    ]) . "\n";
            }
        }
        return $response;
    }

}
