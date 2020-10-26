<?php

/* @var $this yii\web\View */

/* @var $data array */

use yii\helpers\Html;

$this->title = 'Předvýběr kandidátních vět';
?>
<div class="container">
    <h1>WF0: <?= $this->title ?></h1>
    <?=json_encode($data)?>
</div>
