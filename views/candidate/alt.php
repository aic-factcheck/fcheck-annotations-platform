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
            <li><strong><?= $id ?></strong> - <?php
                $par_lines = explode(' .', $block);
                foreach ($par_lines as $line) {
                    if (strlen($line) > 1) {
                        $lines[] = $line . ".";
                    }
                }
                $i = 0;
                foreach ($lines as $line) {
                    $sentence = [
                        "id" => $id,
                        "sentence_id" => $i,
                        "entity" => explode("_", $id)[0],
                        "entity_sentences" => $lines,
                        "sentence" => $line,
                        "context_before" => implode(' ', array_slice($lines, 0, $i)),
                        "context_after" => implode(' ', array_slice($lines, ++$i)),
                    ];
                    echo Html::a($line, ['alt', 'add' => json_encode($sentence)]);
                }
                $sents = explode(" .", $block);
                ?></li>
            <?php
        }
        ?>
    </ul>
</div>
