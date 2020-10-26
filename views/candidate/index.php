<?php

/* @var $this yii\web\View */

/* @var $data array */

use yii\helpers\Html;

$this->title = 'Předvýběr kandidátních vět';
?>
<div class="container">
    <h1>WF0: <?= $this->title ?></h1>
    <p>Ke každé větě se přidá, jako kontext, zbytek odstavce, ve kterém byla nalezena, a slovníček pojmů vybudovaný z
        klíčových slov.</p>
    <h2>Klikněte na větu, která se hodí pro WF1, nebo <?= Html::a('přeskočte', ['index']) ?> na jiný
        vzorek</h2>
    <ul class=" candidate">
        <?php
        foreach ($data as $par) {
            $i = 0;
            $lines = [];
            $par_lines = explode(' .', $par['text']);
            foreach ($par_lines as $line) {
                if (strlen($line) > 1) {
                    $lines[] = $line . ".";
                }
            }
            array_shift($lines);
            $keywords = explode(';', $par['keywords']);
            $dictionary = [];
            foreach ($keywords as $keyword) {
                $dictionary[$keyword] = "todo";
            }
            foreach ($lines as $line) {
                $sentence = [
                    "id" => $par["id"],
                    "sentence_id" => $par["id"] . "_" . $i,
                    "entity" => $par["id"],
                    "entity_sentences" => $lines,
                    "sentence" => $line,
                    "context_before" => implode(' ', array_slice($lines, 0, $i)),
                    "context_after" => implode(' ', array_slice($lines, ++$i)),
                    'dictionary' => $dictionary
                ];
                echo "<li>" . Html::a($line, ['index', 'add' => json_encode($sentence)]) . "</li>";
            }
        }
        ?></ul>
</div>
