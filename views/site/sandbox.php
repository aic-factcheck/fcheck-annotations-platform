<?php

/* @var $this yii\web\View */

use app\models\Claim;
use app\models\Label;
use app\models\User;
use dosamigos\chartjs\ChartJs;
use yii\bootstrap4\Html;
use yii\web\JsExpression;

$this->title = 'Statistiky';


$hiscore = [];
foreach (User::find()->all() as $user) {
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
$tomorrow = strtotime((new DateTime('2020-12-12'))->format('Y-m-d'));
$day = $dayOne = strtotime('2020-11-17');
while ($day < $tomorrow) {
    $activity[date('d.m.Y', $day)] = [
        Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS', 'mutation_type', null])->count(),
        Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS NOT', 'mutation_type', null])->count(),
        Label::find()->where(['oracle' => true])->andWhere(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->count(),
        Label::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['oracle' => false])->count()
    ];
    $annot = [];
    foreach (Claim::find()->where(['>=', 'created_at', $day])->andWhere(['<=', 'created_at', $day + 86400])->andWhere(['IS NOT', 'mutation_type', null])->all()
             as $claim) {
        $annot[] = Label::find()->where(['claim' => $claim->id])->count();
    }
    $avg_labels[] = count($annot) ? (array_sum($annot) / count($annot)) : 0;
    $day += 86400;
}


?>
<div class="container">
    <h1 class="mb-3"><?= $this->title ?></h1>
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
                                            Label::find()->select('claim')->distinct()->where(['label' => "SUPPORTS"])->count(),
                                            Label::find()->select('claim')->distinct()->where(['label' => "REFUTES"])->count(),
                                            Label::find()->select('claim')->distinct()->where(['label' => "NOT ENOUGH INFO"])->count(),
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
                                'label' => 'ú1a',
                                'backgroundColor' => '#28a745',
                                'data' => array_column($activity, 0)
                            ], [
                                'type' => 'bar',
                                'label' => 'ú1b',
                                'backgroundColor' =>
                                    '#dc3545',
                                'data' => array_column($activity, 1)
                            ], [
                                'type' => 'bar',
                                'label' => 'ú2a',
                                'backgroundColor' => '#17a2b8',
                                'data' => array_column($activity, 2)
                            ], [
                                'type' => 'bar',
                                'label' => 'ú2b',
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
                    <h6 class="card-subtitle mb-2 text-muted">dle data vytvoření tvrzení</h6>
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