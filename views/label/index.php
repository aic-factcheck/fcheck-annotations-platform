<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model LabelForm */

use app\models\LabelForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Anotace výroků';
?>
<?php $form = ActiveForm::begin([
    'id' => 'label-form',
]); ?>

<div class="container-fluid">
    <h1>Anotace pravdivosti výroku (WF2)</h1>
    <?php if ($model->sandbox) { ?>
        <div>
            <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
            <div style="color:red;">Claims you write will be recorded. But will not form part of the final dataset.
            </div>
        </div>
    <?php } ?>
</div>

    <p>Výrok: <strong><?=$model->claim->claim?></strong></p>
    <p>Článek: <strong><?=$model->claim->claim?></strong></p>

    <p class="text-right float-right">
        <?= Html::submitButton('Potvrdit výrok', ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Vyvrátit výrok', ['class' => 'btn btn-danger']) ?>
        <?= Html::submitButton('Nelze říct', ['class' => 'btn btn-secondary']) ?>
    </p>
    <?php ActiveForm::end(); ?>