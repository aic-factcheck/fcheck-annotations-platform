<?php

/* @var $this yii\web\View */

/* @var $claims \app\models\Claim[] */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Sbírka nahlášených tvrzení';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php
    foreach ($claims as $claim) {
        try {
            echo "<p><strong>Obměna tvrzení:</strong> \"".$claim->mutatedFrom->claim."\" <strong>extrahovaného z odstavce</strong> \"<em>".$claim->paragraph0->text."</em>\":</p>";
            echo "<h4>" . $claim->claim . "</h4>";
            echo "<p><strong>$claim->comment</strong></p>";
            echo "<hr/>";
        } catch (\yii\base\ErrorException $e) {
            continue;
        }
    }
    ?>
</div>