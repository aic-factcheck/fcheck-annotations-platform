<?php

/* @var $this yii\web\View */

/* @var $summer bool */

use app\models\Claim;
use app\models\Label;
use app\models\User;
use dosamigos\chartjs\ChartJs;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Statistiky';
$summerStamp = strtotime('2021-03-01');
$beginStamp = $summer ? strtotime('2021-03-01') : strtotime('2020-11-01');
$display = ['>=', 'created_at', $beginStamp];
$winterOnly = "WHERE `created_at`<$summerStamp";
$summerOnly = "WHERE `created_at`>$summerStamp";
$contradictions = <<<SQL
SELECT SUM(c) FROM (SELECT COUNT(DISTINCT `label`)>1 as c FROM `label` group by claim) as A
SQL;
$labels = <<<SQL
SELECT SUM(c) FROM (SELECT COUNT(DISTINCT `label`)>=0 as c FROM `label` group by claim) as A
SQL;
$contradictions1 = <<<SQL
SELECT SUM(c) FROM (SELECT COUNT(DISTINCT `label`)>1 as c FROM `label` $summerOnly group by claim) as A
SQL;
$labels1 = <<<SQL
SELECT SUM(c) FROM (SELECT COUNT(DISTINCT `label`)>=0 as c FROM `label` $summerOnly group by claim) as A
SQL;
$contradictions = Yii::$app->db->createCommand($contradictions)->queryScalar();
$contradictions1 = Yii::$app->db->createCommand($contradictions1)->queryScalar();
$labels = Yii::$app->db->createCommand($labels)->queryScalar();
$labels1 = Yii::$app->db->createCommand($labels1)->queryScalar();
$hiscore = [];
$fsv = ['between', 'user', 9, 73];
foreach (User::find()->where($display)->all() as $user) {
    if ($user->id == 110) continue;
    $u = [$user,
        Claim::find()->where(['user' => $user->id,])->andWhere(['IS', 'mutation_type', null])->count(),
        Claim::find()->where(['user' => $user->id,])->andWhere(['IS NOT', 'mutation_type', null])->count(),
        Label::find()->where(['user' => $user->id, 'oracle' => true])->count(),
        Label::find()->where(['user' => $user->id, 'oracle' => false])->count()
    ];
    $u[] = $u[1] + $u[2] + $u[3] + $u[4];
    $hiscore[] = $u;
    $col = array_column($hiscore, 5);
    array_multisort($col, SORT_DESC, $hiscore);
}
$col = array_column($hiscore, 5);
$h = array_slice($hiscore, 0, 8, true);

$annotations = Label::find()
    ->select(['COUNT(*) AS cnt'])
    ->andWhere($display)
    ->groupBy(['claim'])
    ->all();
$hist = [];

foreach ($annotations as $annotation) {
    if (array_key_exists($annotation->cnt, $hist)) {
        $hist[$annotation->cnt]++;
    } else {
        $hist[$annotation->cnt] = 1;
    }
}

$activity = [];
$avg_labels = [];
$tomorrow = strtotime((new DateTime('tomorrow'))->format('Y-m-d'));
$day = $dayOne = $beginStamp;
while ($day < $tomorrow) {
    if (!(($day > strtotime('2020-12-12') && $day < strtotime('2021-03-01')) || $day > strtotime('2021-04-10'))) {
        $activity[date('d.m.Y', $day)] = [
            Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS', 'mutation_type', null])->count(),
            Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS NOT', 'mutation_type', null])->count(),
            Label::find()->where(['oracle' => true])->andWhere(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->count(),
            Label::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['oracle' => false])->count()
        ];
        $annot = [];
        foreach (Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS NOT', 'mutation_type', null])->all()
                 as $claim) {
            $waveEnd = ($day < strtotime('2020-12-13') ? strtotime('2020-12-13') : ($day < strtotime('2021-03-20') ? strtotime('2021-03-20') : strtotime('tomorrow')));
            $annot[] = Label::find()->where(['claim' => $claim->id])->andWhere(['<=', 'created_at', $waveEnd])->count();
        }
        $avg_labels[] = count($annot) ? (array_sum($annot) / count($annot)) : 0;
    }
    $day += 86400;
}

$a = [[], []];
$evidence_count = [];
$evidence_pars = [];

$dataset_str = file_get_contents(__DIR__ .'/'.($summer ? "last_run.jsonl" : "all_time.jsonl"));
$dataset = [];
foreach (explode("\n", $dataset_str) as $datapoint) {
    $datapoint = json_decode($datapoint, true);
    if ($datapoint == null) continue;
    $evs = count($datapoint['evidence']);
    if (!array_key_exists($evs, $evidence_count)) {
        $evidence_count[$evs] = 0;
    }
    $evidence_count[$evs]++;
    foreach ($datapoint['evidence'] as $ev) {
        $pars = count($ev);
        if (!array_key_exists($pars, $evidence_pars)) {
            $evidence_pars[$pars] = 0;
        }
        $evidence_pars[$pars]++;
    }
}
ksort($evidence_count);
ksort($evidence_pars)
shuffle();
?>
<div class="container">
    <h1 class="mb-3"><?= $this->title ?></h1>

    <p>
        <a href="?summer=<?= !$summer ? 1 : 0 ?>" class="btn btn-default">
            <i class="fas fa-toggle-<?= $summer ? 'on' : 'off' ?>"></i> Zobrazit pouze letní semestr
        </a></p>
    <div class="row">
        <div class="col-sm-6">
            <div class="fard">
                <div class="card-body">
                    <h5 class="card-title">Label distribution</h5>
                    <h6 class="card-subtitle mb-2 text-muted">excluding the conflicts</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <p>
                        <?= ChartJs::widget([
                            'type' => 'pie',
                            'id' => 'structurePie',
                            'data' => [
                                'labels' => ['SUPPORTS', 'REFUTES', 'NOT ENOUGH INFO'], // Your labels
                                'datasets' => [
                                    [
                                        'data' => [
                                            Label::find()->select('claim')->distinct()->where(['label' => "SUPPORTS"])->andWhere($display)->count(),
                                            Label::find()->select('claim')->distinct()->where(['label' => "REFUTES"])->andWhere($display)->count(),
                                            Label::find()->select('claim')->distinct()->where(['label' => "NOT ENOUGH INFO"])->andWhere($display)->count(),
                                        ], // Your dataset
                                        'label' => '',
                                        'backgroundColor' => [
                                            '#28a745',
                                            '#dc3545',
                                            '#17a2b8'
                                        ],
                                    ]
                                ]
                            ],
                        ]) ?></p>
                </div>
            </div>
            <div class="fard">
                <div class="card-body">
                    <h5 class="card-title">Number of cross-annotations per claim</h5>
                    <h6 class="card-subtitle mb-2 text-muted">histogram</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <p>
                        <?= ChartJs::widget([
                            'type' => 'bar',
                            'clientOptions' => ['legend' => ['display' => false,]],
                            'data' => [
                                'labels' => array_keys($hist),
                                'datasets' => [
                                    [
                                        'data' => array_values($hist), // Your dataset
                                        'label' => '',
                                        'backgroundColor' => '#007bff',
                                    ]
                                ]
                            ],
                        ]) ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="fard">
                <div class="card-body">
                    <h5 class="card-title">Number of distinct evidence sets</h5>
                    <h6 class="card-subtitle mb-2 text-muted">per claim, histogram</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <p>
                        <?= ChartJs::widget([
                            'type' => 'bar',
                            'clientOptions' => ['legend' => ['display' => false,]],
                            'data' => [
                                'labels' => array_keys($evidence_count),
                                'datasets' => [
                                    [
                                        'data' => array_values($evidence_count), // Your dataset
                                        'label' => '',
                                        'backgroundColor' => '#dc3545',
                                    ]
                                ]
                            ],
                        ]) ?></p>
                </div>
            </div>

            <div class="fard">
                <div class="card-body">
                    <h5 class="card-title"><em>Evidence set</em> size</h5>
                    <h6 class="card-subtitle mb-2 text-muted">histogram</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <p>
                        <?= ChartJs::widget([
                            'type' => 'bar',
                            'clientOptions' => ['legend' => ['display' => false,]],
                            'data' => [
                                'labels' => array_keys($evidence_pars),
                                'datasets' => [
                                    [
                                        'data' => array_values($evidence_pars), // Your dataset
                                        'label' => '',
                                        'backgroundColor' => '#17a2b8',
                                    ]
                                ]
                            ],
                        ]) ?></p>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
    </div>
</div>