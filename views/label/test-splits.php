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
$bad = '<span class="danger"><i class="fas fa-times"></i></span>';
?>
<?php $form = ActiveForm::begin([
    'id' => 'splits-form',
    'layout' => 'horizontal',
]); ?>

<div class="container">
    <h1><?= $this->title ?></h1>
    <p>Nahráním trojice souborů (příp. vyplněním času exportu) necháte tyto validovat proti údajům v databázi.</p><br/>
    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => 'text/jsonl']) ?>
    <?= $form->field($model, 'datetime')->widget(DateControl::class, [
        'type' => DateControl::FORMAT_DATE
    ]); ?>
    <p class="">
        <?= Html::submitButton('<i class="fas fa-upload"></i> Nahrát', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success', 'disabled' => true]) ?>
    </p>
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
            <td></td>
            <td></td>
            <td><?=$ok?></td>
        </tr>
        <tr>
            <th>Počet labelů [SUP,REF,NEI]</th>
            <td></td>
            <td></td>
            <td><?=$ok?></td>
        </tr>
        <tr>
            <th>Podmíněné labely jsou NEI</th>
            <td>-</td>
            <td>-</td>
            <td><?=$ok?></td>
        </tr>
        <tr>
            <th>Počet článků [exp't, nepočítá softdely]</th>
            <td></td>
            <td></td>
            <td><?=$ok?></td>
        </tr>
    </table>
    <br/>
    <h2><i class="fas fa-chart-pie"></i> Vnitřní integrita</h2>
    <table class="table table-striped">
        <tr>
            <th>Kritérium</th>
            <th>train</th>
            <th>dev</th>
            <th>test</th>
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
            <th>train-dev</th>
            <th>dev-test</th>
            <th>test-train</th>
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

</div>
<?php ActiveForm::end(); ?>
