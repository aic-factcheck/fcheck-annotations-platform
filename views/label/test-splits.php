<?php

/* @var $this yii\web\View */

/* @var $model \app\models\SplitsForm */


use app\helpers\Entity;
use app\helpers\Helper;
use app\models\LabelForm;
use kartik\datecontrol\DateControl;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Validátor splitů';
$ok = '<span class="text-success"><i class="fas fa-check"></i></span>';
$bad = '<span class="text-danger"><i class="fas fa-times"></i></span>';
?>
<?php $form = ActiveForm::begin([
    'id' => 'splits-form',
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<div class="container">
    <h1><?= $this->title ?></h1>
    <p>Nahráním trojice souborů (příp. vyplněním času exportu) necháte tyto validovat proti údajům v databázi.</p><br/>
    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'datetime')->widget(DateControl::class, [
        'type' => DateControl::FORMAT_DATE
    ]); ?>
    <p class="">
        <?= Html::submitButton('<i class="fas fa-upload"></i> Nahrát', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success']) ?>
    </p>
    <?php
    if ($model->_ready) {
        $train = $model->_splits[$model->_names[0]];
        $val = $model->_splits[$model->_names[1]];
        $test = $model->_splits[$model->_names[2]];
        ?>
        <br/><br/>
        <h2><i class="fas fa-database"></i> DB Integrita</h2>
        <table class="table table-striped">
            <tr>
                <th>Kritérium</th>
                <th>Splity</th>
                <th>DB</th>
                <th></th>
            </tr>
            <tr>
                <th>Počet tvrzení</th>
                <td><?= $a = count($model->_all) ?></td>
                <td><?= $b = count($model->_claims) ?></td>
                <td><?= $a == $b ? $ok : $bad ?></td>
            </tr>
            <tr>
                <th>Počet labelů [SUP,REF,NEI]</th>
                <td><?= $a = implode(",", $model->_label_count) ?></td>
                <td><?= $b = implode(",", $model->_label_count_db) ?></td>
                <td><?= $a == $b ? $ok : $bad ?></td>
            </tr>
            <tr>
                <th>Podmíněné labely jsou NEI</th>
                <td><?php
                    $labels = \app\models\Label::find()->andWhere(['not', ['condition' => null]])->groupBy('claim')->all();
                    $a = 0;
                    foreach ($labels as $label) {
                        $a += (array_key_exists($label->claim, $model->_all) && $model->_all[$label->claim]["label"] == "NOT ENOUGH INFO");
                    }
                    echo $a;
                    ?></td>
                <td><?= $b = count($labels) ?></td>
                <td><?= $a == $b ? $ok : $bad ?></td>
            </tr>
            <tr>
                <th>Počet článků [exp't, nepočítá softdely]</th>
                <td></td>
                <td></td>
                <td><?= $ok ?></td>
            </tr>
        </table>
        <br/>
        <h2><i class="fas fa-chart-pie"></i> Vnitřní integrita</h2>
        <table class="table table-striped">
            <tr>
                <th>Kritérium</th>
                <th><?= $model->_names[0] ?></th>
                <th><?= $model->_names[1] ?></th>
                <th><?= $model->_names[2] ?></th>
            </tr>
            <tr>
                <th>Podíl</th>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>Rozložení labelů</th>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br/>
        <h2><i class="fas fa-faucet"></i> Leakage (překryv splitů)</h2>
        <table class="table table-striped">
            <tr>
                <th>Úrověň</th>
                <th><?= $model->_names[0] ?>-<?= $model->_names[1] ?></th>
                <th><?= $model->_names[1] ?>-<?= $model->_names[2] ?></th>
                <th><?= $model->_names[2] ?>-<?= $model->_names[0] ?></th>
                <th></th>
            </tr>
            <tr>
                <th>Zdrojový článek</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>Ú<sub>1a</sub> tvrzení</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>Ú<sub>1b</sub> tvrzení</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        </table>
        <br/>

        <?php
    }
    ?>
</div>
<?php ActiveForm::end(); ?>
