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
            $lines = preg_split('/\d+\t/', $par['lines']);
            array_shift($lines);
            $keywords = explode(';', $par['keywords']);
            $dictionary = [];
            foreach ($keywords as $keyword) {
                $dictionary[$keyword] = "todo";
            }
            foreach ($lines as $line) {
                //{"id":200,"sentence_id":"200","entity":"T\u0159i mrtv\u00ed p\u0159i hav\u00e1rii letadla v Rakousku","sentence":"P\u0159\u00ed\u010diny ne\u0161t\u011bst\u00ed se vy\u0161et\u0159uj\u00ed , napsala rakousk\u00e1 agentura APA .","dictionary":[],"context_before":"20000818E02531 V\u00cdDE\u0147 18. srpna ( \u010cTK ) - P\u0159i hav\u00e1rii jednomotorov\u00e9ho letadla s n\u011bmeckou imatrikulac\u00ed u obce St.Michael v rakousk\u00e9m \u0160t\u00fdrsku zahynuly dnes v\u0161echny t\u0159i osoby na palub\u011b . Ozn\u00e1mila to rakousk\u00e1 policie , podle kter\u00e9 \u0161lo o n\u011bmeck\u00e9ho pilota a dva rakousk\u00e9 pasa\u017e\u00e9ry . K hav\u00e1rii do\u0161lo odpoledne hned po startu letadla z nedalek\u00e9ho leti\u0161t\u011b Traboch- Timmersdorf . Kr\u00e1tce p\u0159edt\u00edm letadlo dovezlo sou\u010d\u00e1stky pro bl\u00edzkou tov\u00e1rnu na polovodi\u010de .","context_after":"ik ank"}
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
