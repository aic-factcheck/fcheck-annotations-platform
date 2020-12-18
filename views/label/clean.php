<?php

/* @var $this yii\web\View */
/* @var $conflicts \app\models\Label[][] */

use yii\bootstrap4\Html;

$this->title = 'Protiřečící si anotace';
?>
<div class="container">
    <h1><?=$this->title?></h1>
    <?php
    foreach ($conflicts as $conflict){
        echo "<h2>".$conflict[0]->claim0->claim."</h2>";
        foreach ($conflict as $label){
            echo "<h3> <strong>$label->label</strong>: <input type='checkbox' name='delete[]' value='$label->id'> smazat</h3> ";
            foreach ($label->evidences as $evidence){
                echo "<p>".$evidence->paragraph0->text."</p>";
            }
        }
    }
    ?>
</div>