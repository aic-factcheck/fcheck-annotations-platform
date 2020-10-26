<?php

/* @var $this yii\web\View */

/* @var $data array */

use yii\helpers\Html;

$this->title = 'Předvýběr kandidátních vět';
?>
<div class="container">
    <h1>WF0: <?= $this->title ?></h1>
    <ul>
        <?php
        foreach ($data['blocks'] as $id => $block) {
            ?>
            <li><strong><?=$id?></strong> - <?=$block?></li>
            <?php
        }
        ?>
    </ul>
</div>
