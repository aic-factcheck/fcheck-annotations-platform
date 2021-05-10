<?php

/* @var $this yii\web\View */

/* @var $claims \app\models\Claim[] */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
//„A“
$this->title = 'Sbírka nahlášených tvrzení';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php
    foreach ($claims as $claim) {
        try {
            echo "<p><strong>Obměna tvrzení:</strong> „<em>".$claim->mutatedFrom->claim."</em>“ <strong>extrahovaného z odstavce</strong> „<em>".$claim->paragraph0->text."</em>“:</p>";
            echo "<h3>„" . $claim->claim . "“</h3>";
            echo "<p><strong>$claim->comment</strong></p>";
            echo "<hr/>";
        } catch (\yii\base\ErrorException $e) {
            continue;
        }
    }
    ?>
</div>