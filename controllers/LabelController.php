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

    public function actionMislassifications()
    {
        $misclas = json_decode('
[{"claim":"326","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":97.95},{"claim":"326","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.66},{"claim":"147","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.63},{"claim":"83","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.64},{"claim":"429","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.72},{"claim":"659","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.67},{"claim":"833","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.64},{"claim":"522","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":72.64},{"claim":"1071","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.29},{"claim":"1140","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.75},{"claim":"1116","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.15},{"claim":"1116","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":96.41},{"claim":"1126","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.72},{"claim":"433","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.78},{"claim":"505","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.63},{"claim":"1779","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.77},{"claim":"519","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":70.35},{"claim":"2122","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.54},{"claim":"2129","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.62},{"claim":"663","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":98.14},{"claim":"2676","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.76},{"claim":"2680","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.36},{"claim":"2900","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":59.56},{"claim":"2126","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":68.65},{"claim":"3605","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":54.62},{"claim":"3782","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":92.39},{"claim":"3782","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":91.64},{"claim":"3965","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.44},{"claim":"4034","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.53},{"claim":"657","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.54},{"claim":"1365","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.72},{"claim":"2152","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.66},{"claim":"4258","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":98.77},{"claim":"4283","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.76},{"claim":"4447","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.65},{"claim":"661","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.59},{"claim":"432","trueLabel":"REFUTES","prediction":"SUPPORTS","certainty":99.80},{"claim":"472","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":50.50},{"claim":"751","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":65.08},{"claim":"2397","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":57.07},{"claim":"2680","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":99.33},{"claim":"2680","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":99.67},{"claim":"2903","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":59.80},{"claim":"3707","trueLabel":"REFUTES","prediction":"NOT ENOUGH INFO","certainty":98.87},{"claim":"1115","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":98.37},{"claim":"750","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.42},{"claim":"1362","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.73},{"claim":"1410","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.56},{"claim":"2632","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.53},{"claim":"2632","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.57},{"claim":"2725","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.61},{"claim":"2725","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.73},{"claim":"3214","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":85.54},{"claim":"3237","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":98.05},{"claim":"3237","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":98.41},{"claim":"3238","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":98.79},{"claim":"3235","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":97.80},{"claim":"3615","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.19},{"claim":"4098","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":98.75},{"claim":"149","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.61},{"claim":"4256","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":90.29},{"claim":"4281","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.72},{"claim":"4451","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.76},{"claim":"598","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":99.63},{"claim":"1522","trueLabel":"SUPPORTS","prediction":"REFUTES","certainty":95.02},{"claim":"1006","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":64.64},{"claim":"2935","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":85.42},{"claim":"2932","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":98.45},{"claim":"3238","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":98.60},{"claim":"3235","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":99.50},{"claim":"3703","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":96.31},{"claim":"3704","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":99.81},{"claim":"3718","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":63.96},{"claim":"4102","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":99.81},{"claim":"4102","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":95.07},{"claim":"4098","trueLabel":"SUPPORTS","prediction":"NOT ENOUGH INFO","certainty":99.67},{"claim":"4459","trueLabel":"NOT ENOUGH INFO","prediction":"REFUTES","certainty":95.97},{"claim":"428","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":47.20},{"claim":"1189","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":91.01},{"claim":"3423","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":98.19},{"claim":"3855","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":99.71},{"claim":"556","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":74.43},{"claim":"1944","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":99.75},{"claim":"1106","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":73.49},{"claim":"518","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":82.65},{"claim":"508","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":95.36},{"claim":"2197","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":92.75},{"claim":"1008","trueLabel":"NOT ENOUGH INFO","prediction":"SUPPORTS","certainty":52.33}]
', true);

        if (isset($_POST['delete']) || isset($_POST['relabel'])){
            if (isset($_POST['delete'])) {
                foreach (Label::find()->where(['id' => $_POST['delete']])->all() as $label) {
                    $label->deleted = 1;
                    $label->save();
                }
                Yii::$app->session->addFlash('success', 'Označené anotace byly smazány.');
            }
            if (isset($_POST['relabel'])) {
                // TODO
                Yii::$app->session->addFlash('success', 'Označené anotace byly překlasifikovány.');
            }
            return $this->refresh();
        }
        $i=0;
        foreach ($misclas as $miscla){
            $misclas[$i]["claim_"] = Claim::find()->andWhere(['id'=>$miscla['claim']])->one();
            $misclas[$i++]["labels"] = Label::find()->andWhere(['claim'=>$miscla['claim'],'label'=>$miscla['trueLabel']])->all();
        }

        return $this->render("misclassifications", ["misclassifications" => $misclas]);
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

    public function actionExport2($shuffle = false, $evidenceFormat = 'ctkId', $summer = false, $fever = false, $simulateNei = false, $printCtr = false, $condition = 'none')
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
                            "evidence" => array_values($evidenceSets), "source" => $claim->paragraph0->ctkId,
                            "verifiable" => ($label == "NOT ENOUGH INFO" ? "NOT " : "") . "VERIFIABLE"], JSON_UNESCAPED_UNICODE) . "\n";
                }
            }
        }

        return ($printCtr ? (json_encode($ctr) . "\n") : '') . $response;
    }

    public function actionExport($evidenceFormat = 'ctkId', $fever = false, $simulateNeiEvidence = false)
    {
        $response = "";
        Yii::$app->response->format = Response::FORMAT_RAW;
        foreach (Claim::find()->andWhere(['not', ['mutation_type' => null]])->all() as $claim) {
            $label = $claim->getMajorityLabel();
            if ($label == null) continue;
            $evidenceSets = $claim->getEvidenceSets2($label, $evidenceFormat, $fever, $simulateNeiEvidence);
            $response .= json_encode([
                    "id" => $claim->id,
                    "label" => $claim->getMajorityLabel(),
                    "claim" => Helper::detokenize($claim->claim),
                    "evidence" => array_values($evidenceSets), "source" => $claim->paragraph0->ctkId,
                    "verifiable" => ($label == "NOT ENOUGH INFO" ? "NOT " : "") . "VERIFIABLE"
                ], JSON_UNESCAPED_UNICODE) . "\n";
        }
        $u = (Yii::$app->user->isGuest ? 'guest' : Yii::$app->user->id);
        file_put_contents('../runtime/debug/export_' . date('m-d-Y_hia') . '_' . $u . '.jsonl', $response);
        return $response;
    }

    public function actionFlags()
    {
        $claims = Claim::find(1)->andWhere(['like', 'comment', '%flag%', false])->all();
        return $this->render("flags", ["claims" => $claims]);
    }

}
