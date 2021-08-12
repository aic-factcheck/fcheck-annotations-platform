<?php

/* @var $this yii\web\View */

/* @var $misclassifications array */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Protiřečící si anotace';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php ActiveForm::begin() ?>
    <p>
        <?= Html::button('<i class="fas fa-times"></i>  Uložit úpravy', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </p>
    <?php
    foreach ($misclassifications as $misclassification) {
        try {
            echo "<h2>".$misclassification['claim']." (". (\app\models\Claim::findOne($misclassification['claim']))->getMajorityLabel()." &raquo; ".$misclassification.")</h2>";
            echo "<p>".json_encode($misclassification)."</p>";
            echo "<hr/>";
        } catch (\yii\base\ErrorException $e) {
            echo $e;
            continue;
        }
    }
    ?>
    <p>
        <?= Html::button('<i class="fas fa-times"></i>  Uložit úpravy', ['class' => 'btn btn-primary']) ?>
    </p>
    <?php ActiveForm::end() ?>
</div>