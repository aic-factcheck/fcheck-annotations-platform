<?php

/* @var $this yii\web\View */

/* @var $conflicts Label[][] */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Protiřečící si anotace';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php ActiveForm::begin() ?>
    <p>
        <?= Html::button('<i class="fas fa-times"></i> Smazat vybrané (měkké smazání)', ['type' => 'submit', 'class' => 'btn btn-danger']) ?>
    </p>
    <?php
    foreach ($conflicts as $conflict) {
        echo "<h2>" . $conflict[0]->claim0->claim . "</h2>";
        foreach ($conflict as $label) {
            echo "<h3><label><input type='checkbox' name='delete[]' value='$label->id'/> $label->label";
            if (strlen($label->condition) > 0) {
                echo " (Podmínka: $label->condition)";
            }
            echo "</label></h3>";
            foreach ($label->evidences as $evidence) {
                echo "<p>" . ' ' . \app\helpers\Helper::detokenize($evidence->paragraph0->text) . " " . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($evidence->paragraph0->article0->date), ['class' => 'badge badge-secondary ']);
            }
        }
        echo "<hr/>";
    }
    ?>
    <p>
        <?= Html::button('<i class="fas fa-times"></i> Smazat vybrané (měkké smazání)', ['class' => 'btn btn-danger']) ?>
    </p>
    <?php ActiveForm::end() ?>
</div>