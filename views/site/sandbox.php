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

?>
<div class="container">
    <h1 class="mb-3"><?= $this->title ?></h1>

    <div class="row mb-5">
        <div class="col-lg-5">
            <table class="table table-striped">
                <tr>
                    <th></th>
                    <th>∑<sub>zima</sub></th>
                    <th>∑<sub>léto</sub></th>
                    <th>∑</th>
                </tr>
                <tr>
                    <th>Ú1a - Extrahovaná tvrzení</th>
                    <td><?= $a[0][] = Claim::find()->where(['<=', 'created_at', $summerStamp])->andWhere(['IS', 'mutation_type', null])->count() ?></td>
                    <td><?= $a[1][] = Claim::find()->where(['>=', 'created_at', $summerStamp])->andWhere(['IS', 'mutation_type', null])->count() ?></td>
                    <td><?= $a[2][] = Claim::find()->andWhere(['IS', 'mutation_type', null])->count() ?></td>
                </tr>
                <tr>
                    <th>Ú1b - Odvozená tvrzení</th>
                    <td><?= $a[0][] = Claim::find()->where(['<=', 'created_at', $summerStamp])->andWhere(['IS NOT', 'mutation_type', null])->count() ?></td>
                    <td><?= $a[1][] = Claim::find()->where(['>=', 'created_at', $summerStamp])->andWhere(['IS NOT', 'mutation_type', null])->count() ?></td>
                    <td><?= $a[2][] = Claim::find()->andWhere(['IS NOT', 'mutation_type', null])->count() ?></td>
                </tr>
                <tr>
                    <th>Ú2a - Referenční anotace</th>
                    <td><?= $a[0][] = Label::find()->andWhere(['<=', 'created_at', $summerStamp])->andWhere(['=', 'oracle', true])->count() ?></td>
                    <td><?= $a[1][] = Label::find()->andWhere(['>=', 'created_at', $summerStamp])->andWhere(['=', 'oracle', true])->count() ?></td>
                    <td><?= $a[2][] = Label::find()->andWhere(['=', 'oracle', true])->count() ?></td>
                </tr>
                <tr>
                    <th>Ú2b - Anotace výroků</th>
                    <td><?= $a[0][] = Label::find()->andWhere(['<=', 'created_at', $summerStamp])->andWhere(['=', 'oracle', false])->count() ?></td>
                    <td><?= $a[1][] = Label::find()->andWhere(['>=', 'created_at', $summerStamp])->andWhere(['=', 'oracle', false])->count() ?></td>
                    <td><?= $a[2][] = Label::find()->andWhere(['=', 'oracle', false])->count() ?></td>
                </tr>
                <tr>
                    <th>∑<sub>Všechny úkoly</sub></th>
                    <th><?= array_sum($a[0]) ?></th>
                    <th><?= array_sum($a[1]) ?></th>
                    <th><?= array_sum($a[2]) ?></th>
                </tr>
                <tr>
                    <th>Počet anotátorů</th>
                    <td><?= $c_1 = User::find()->where(['<=', 'created_at', $summerStamp])->count() ?></td>
                    <td><?= $c_2 = User::find()->where(['>=', 'created_at', $summerStamp])->count() ?></td>
                    <td><?= $c_1 + $c_2 ?></td>
                </tr>
                <tr>
                    <th>Anotovaná tvrzení</th>
                    <td><?= $labels - $labels1 ?></td>
                    <td><?= $labels1 ?></td>
                    <td><?= $labels ?></td>
                </tr>
                <tr>
                    <th>Rozpory anotací</th>
                    <td><?= $contradictions - $contradictions1 ?></td>
                    <td><?= $contradictions1 ?></td>
                    <td><?= $contradictions ?></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-7">
            <img src="<?= Url::to(['/images/nakres.png']) ?>" style="width:100%">
        </div>
    </div>
    <p>
        <a href="?summer=<?= !$summer ? 1 : 0 ?>" class="btn btn-default">
            <i class="fas fa-toggle-<?= $summer ? 'on' : 'off' ?>"></i> Zobrazit pouze letní semestr
        </a></p>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pravdivost tvrzení</h5>
                    <h6 class="card-subtitle mb-2 text-muted">která nemají rozporuplné anotace</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <p>
                        <?= ChartJs::widget([
                            'type' => 'pie',
                            'id' => 'structurePie',
                            'data' => [
                                'labels' => ['Potvrzená', 'Vyvrácená', 'Nelze dokázat'], // Your labels
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
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Počty anotací tvrzení</h5>
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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nejaktivnější anotátoři</h5>
                    <h6 class="card-subtitle mb-2 text-muted">dle počtu splněných úkolů</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Uživatel</th>
                            <th>Ú<sub>1</sub>a</th>
                            <th>Ú<sub>1</sub>b</th>
                            <th>Ú<sub>2</sub>a</th>
                            <th>Ú<sub>2</sub>b</th>
                            <th>∑</th>
                        </tr>
                        <?php
                        foreach ($h as $k => $u) {
                            $k++;
                            echo "<tr><th>$k</th><th>$u[0]</th><td>$u[1]</td><td>$u[2]</td><td>$u[3]</td><td>$u[4]</td><th>$u[5]</th></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Počet různých důkazů tvrzení</h5>
                    <h6 class="card-subtitle mb-2 text-muted">histogram</h6>
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
        </div>
        <div class="col-lg-4 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Počet odstavců v důkazu</h5>
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
        <div class="col-lg-4 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Shoda anotací</h5>
                    <h6 class="card-subtitle mb-2 text-muted">pravdivosti tvrzení</h6>
                    <p>Berte s rezervou, teprve vyhodnocujeme.</p>
                    <table class="table table-striped table-sm">
                        <tr>
                            <th>Metrika</th>
                            <th>=</th>
                            <th>Shoda</th>
                        </tr>
                        <tr>
                            <td>Shoda po párech</td>
                            <td>74%</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cohenovo κ po párech</td>
                            <td>0.58</td>
                            <td>Přiměřená</td>
                        </tr>
                        <tr>
                            <td>Fleissovo κ trojic</td>
                            <td>0.57</td>
                            <td>Přiměřená</td>
                        </tr>
                        <tr>
                            <td>Fleissovo κ čtveřic</td>
                            <td>0.63</td>
                            <td>Značná</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-12 my-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Aktivita v systému</h5>
                    <h6 class="card-subtitle mb-2 text-muted">dle počtu splněných úkolů</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    `<?= ChartJs::widget([
                        'type' => 'bar',
                        'options' => [

                        ],
                        'data' => [
                            'labels' => array_keys($activity),
                            'datasets' => [[
                                'type' => 'bar',
                                'label' => 'claim extraction',
                                'backgroundColor' => '#28a745',
                                'data' => array_column($activity, 0)
                            ], [
                                'type' => 'bar',
                                'label' => 'claim mutation',
                                'backgroundColor' =>
                                    '#dc3545',
                                'data' => array_column($activity, 1)
                            ], [
                                'type' => 'bar',
                                'label' => 'self-verification',
                                'backgroundColor' => '#17a2b8',
                                'data' => array_column($activity, 2)
                            ], [
                                'type' => 'bar',
                                'label' => 'other\'s claim verification',
                                'backgroundColor' => '#007bff',
                                'data' => array_column($activity, 3)
                            ],
                            ]
                        ],
                        'clientOptions' =>
                            [
                                'options' => [
                                    'title' => [
                                        'display' => true,
                                    ],
                                    'tooltips' => [
                                        'mode' => 'label'
                                    ],
                                    'responsive' => true,
                                ],

                                'scales' => [
                                    'xAxes' => [
                                        [['stacked' => true,]]
                                    ],
                                    'yAxes' => [
                                        [
                                            'stacked' => true,
                                            'position' => 'left',
                                            'id' => "y-axis-0",
                                        ],
                                        [
                                            'stacked' => false,
                                            'position' => 'right',
                                            'id' => "y-axis-1",
                                        ],

                                    ]
                                ],
                            ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 my-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Průměrný počet anotací na jedno tvrzení</h5>
                    <h6 class="card-subtitle mb-2 text-muted">dle data vytvoření tvrzení, pouze anotace z aktivní
                        vlny.</h6>
                    <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                    `<?= ChartJs::widget([
                        'type' => 'bar',
                        'clientOptions' => ['legend' => ['display' => false,]],
                        'data' => [
                            'labels' => array_keys($activity),
                            'datasets' => [[
                                'type' => 'bar',
                                'label' => '',
                                'backgroundColor' => '#28a745',
                                'data' => array_values($avg_labels)
                            ],
                            ]
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
    </div>
</div>