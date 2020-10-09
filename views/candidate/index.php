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
                $json = '{"id": 200, "sentence_id": "200", "entity": "Tři mrtví při havárii letadla v Rakousku", "sentence": "Příčiny neštěstí se vyšetřují , napsala rakouská agentura APA .", "dictionary": {}, "context_before": "", "context_after": "}';
                echo "<li>$line</li>";
            }
        }
        ?></ul>
</div>
