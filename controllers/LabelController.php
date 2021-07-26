<?php

namespace app\controllers;

use app\helpers\Helper;
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
                        'actions' => ['export', 'jsonl', 'rys'],
                        'allow' => true,
                    ],
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
                    ->andWhere(['sandbox' => $sandbox])
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
                'claim' => $claim,
                'unannotated' => $unannotated,
            ]);
        }

        $model = new LabelForm($sandbox, $oracle, Claim::findOne($claim));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Anotace úspěšně uložena.");
            return $this->redirect(['index', 'sandbox' => $sandbox, 'oracle' => $oracle, 'unannotated' => $unannotated,]);

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
        $c = array_unique($c);
        foreach ($c as $conflict) {
            $result[] = $labels[$conflict];
        }
        return $this->render("clean", ["conflicts" => $result]);
    }

    public function actionJsonl()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $labels = [];
        foreach (Label::find()->orderBy('id')->all() as $label) {
            if ($label->label != null) {
                if (array_key_exists($label->claim, $labels) && ($labels[$label->claim] == null || $labels[$label->claim]->label != $label->label)) {
                    $labels[$label->claim] = null;
                } else {
                    $labels[$label->claim] = $label;
                    if (!empty($label->condition)) {
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
                    ], JSON_UNESCAPED_UNICODE) . "\n";
            }
        }
        return $response;
    }

    public function actionExport($shuffle = false, $evidenceFormat = 'ctkId', $summer = false, $fever = false, $simulateNei = false, $printCtr = false, $condition = 'none')
    {
        if ($fever) {
            return $this->actionJsonl();
        }
        $beginStamp = $summer ? strtotime('2021-03-01') : strtotime('2020-09-01');
        $ctr = ["SUPPORTS" => 0, "REFUTES" => 0, "NOT ENOUGH INFO" => 0];
        $response = "";
        Yii::$app->response->format = Response::FORMAT_RAW;
        foreach (Claim::find()->andWhere(['not', ['mutation_type' => null]])->andWhere(['>=', 'created_at', $beginStamp])->orderBy($shuffle ? new Expression('rand()') : 'paragraph,id')->all() as $claim) {
            $labels = $claim->getEvidenceSets($evidenceFormat, $simulateNei, $condition);
            foreach ($labels as $label => $evidenceSets) {
                if ((!empty($evidenceSets) and $label != null) || $label == "NOT ENOUGH INFO") {
                    $ctr[$label] += count($evidenceSets);
                    $response .= json_encode(["id" => $claim->id, "label" => $label, "claim" => Helper::detokenize($claim->claim),
                            "evidence" => array_values($evidenceSets), "source" => $claim->paragraph0->ctkId], JSON_UNESCAPED_UNICODE) . "\n";
                }
            }
        }

        return ($printCtr ? (json_encode($ctr) . "\n") : '') . $response;
    }

    public function actionFlags()
    {
        $claims = Claim::find(1)->andWhere(['like', 'comment', '%flag%', false])->all();
        return $this->render("flags", ["claims" => $claims]);
    }

}
