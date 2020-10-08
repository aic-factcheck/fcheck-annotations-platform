<?php

/* @var $this yii\web\View */
/* @var $data array */

$this->title = 'Předvýběr kandidátních vět';
?>
<div class="container">
    <h1>WF0: <?= $this->title ?></h1>
    <ul>
        <?php
        foreach ($data as $par) {
            $lines = preg_split('/\d+\t/', $par['lines']);
            foreach ($lines as $line) {
                echo "<li>$line</li>";
            }
        }
        ?></ul>
</div>
