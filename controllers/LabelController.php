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

    public function actionIndex($sandbox = false, $oracle = false, $claim = null, $unannotated = false)
    {
        if ($claim == null) {
            $traversed = [];
            do {
                $expr = '(rand() + (c.id > 2275) + ' . ($unannotated ? 1000 : 1) . '*((SELECT COUNT(*) FROM label l where l.claim = c.id)<1)) desc';
                $claim = Claim::find()
                    ->alias('c')
                    ->where(['sandbox' => $sandbox])
                    ->andWhere(['is not', 'mutation_type', null])
                    ->andWhere(['not in', 'id', $traversed])
                    ->andWhere([$oracle ? '=' : '<>', 'user', Yii::$app->user->id])
                    ->orderBy(new Expression($expr))
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

    public function actionClean()
    {
        if (isset($_POST['delete'])) {
            foreach (Label::find()->where(['id' => $_POST['delete']])->all() as $label) {
                $label->deleted = 1;
                $label->save();
            }
            Yii::$app->session->addFlash('success', 'Označené anotace byly smazány.');
            return $this->refresh();
        }
        $labels = [];
        $c = [];
        foreach (Label::find()->all() as $label) {
            if ($label->condition != null) {
                //$label->label = "NOT ENOUGH INFO";
            }
            if ($label->label != null) {
                if (array_key_exists($label->claim, $labels)) {
                    $labels[$label->claim][] = $label;
                    if ($labels[$label->claim][0]->label != $label->label) {
                        $c[] = $label->claim;
                    }
                } else {
                    $labels[$label->claim] = [$label];
                }
            }
        }
        $result = [];
        foreach ($c as $conflict) {
            $result[] = $labels[$conflict];
        }
        return $this->render("clean", ["conflicts" => $result]);
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
            if ($label !== null && $label->claim0 !== null) {
                $evidences = [];
                foreach ($label->evidences as $evidence) {
                    if (!array_key_exists($evidence->group, $evidences))
                        $evidences[$evidence->group] = [];
                    $evidences[$evidence->group][] = [
                        $label->id,
                        ($evidence->group * 1000000 + $label->id),
                        $label->claim0->paragraph0->getCtkId(),
                        0
                    ];
                }
                $evidences = array_values($evidences);
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
