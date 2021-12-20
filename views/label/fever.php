<?php

/* @var $this yii\web\View */

/* @var $pair FeverPair */
/* @var $goal int */
/* @var $done int */

use app\models\FeverPair;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\Helper;

$this->title = 'Kontrola CsFEVER Důkazů';
$this->registerJs(
    <<<JS
$(document).ready(function() {
    $(document).keydown(function (e) {
      if ((e.ctrlKey||e.metaKey) && e.keyCode == 49) {
            $(".btn-success")[0].click(); e.preventDefault();
      }
      if ((e.ctrlKey||e.metaKey) && e.keyCode == 50) {
            $(".btn-danger")[0].click(); e.preventDefault();
      }
      if ((e.ctrlKey||e.metaKey) && e.keyCode == 51) {
            $(".btn-secondary")[0].click(); e.preventDefault();
      }
      if ((e.ctrlKey||e.metaKey)  && e.keyCode == 52) {
            $(".btn-warning")[0].click(); e.preventDefault();
      }
    });
});
JS
);
$i = 1;
?>
<div class="container">

    <h1>Ú<sub>3</sub>: <?= $this->title ?></h1>
    <p><strong>Statistiky: zatím zkontrolováno <span class="text-primary"><?= $done ?></span> z cíle <span class="text-primary"><?= $goal ?></span> CsFEVER párů důkaz-tvrzení</p></strong>
    <div class="alert alert-warning mt-0" role="alert">
        <h3 class="alert-heading">Pokyny</h3>
        <ul>
            <li>Cílem úkolu je během zhruba 30 sekund identifikovat, zda je navržený důkaz CsFEVER tvrzení <strong>validní</strong>.</li>
            <li>Tzn. jestli navržený důkaz dostačuje k vyvození navrženého verdiktu (Potvrzeno, Vyvráceno)</li>
            <li>Výsledkem anotace je nový verdikt, <strong>Potvrzeno, Vyvráceno</strong> nebo <strong>Nedostatek informací</strong>, který zvolí anotátor podle informací, které najde v navrženém důkaze vč. nadpisů.</li>
            <li>Tyto informace není třeba dále označovat.</li>
            <li>Klávesové zkratky (správný důkaz):
                <span class="text-success"> <i class="fas fa-check"></i> [Ctrl+1, ⌘1]</span>,
                <span class="text-danger"> <i class="fas fa-times"></i> [Ctrl+2, ⌘2]</span>,
                <span class="text-secondary"> <i class="far fa-question-circle"></i> [Ctrl+3, ⌘3]</span>,
                <span class="text-warning"> <i class="fas fa-exclamation-triangle"></i> [Ctrl+4, ⌘4]</span>
            </li>
        </ul>
    </div>

    <div class="card bg-light mb-3">
        <div class="card-body">
            <h4>Tvrzení</h4>
            <div class="card bg-white mb-2">
                <div class="card-body text-block">
                    <h5 class="pb-0 mb-0">
                        <?= $pair->claim_cs ?> <br /><small class="text-secondary">(Orig.: <?= $pair->claim ?>)</small>
                    </h5>
                </div>
            </div>
            <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
            <br />
            <h4>Navržený důkaz: <?= Html::tag('span', $pair->label, ['class' => ($pair->label == "SUPPORTS" ? 'text-success' : 'text-danger')]) ?></h4>
            <?php
            foreach (json_decode($pair->evidence_cs) as $key => $text) {
                Helper::setEntities(explode(" ", $key));
            ?>
                <div class="card bg-white mb-2">
                    <div class="card-body text-block">
                        <strong>[<?= $key ?>] </strong><?= Helper::presentText($text) ?>
                    </div>
                </div>
            <?php
            }
            ?>

            <h4 class="mt-4">Správný důkaz:
                <?= Html::a('<i class="fas fa-check"></i> Potvrzeno', ['label/fever', 'fever_pair' => $pair->id, 'label' => 'SUPPORTS'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-times"></i> Vyvráceno', ['label/fever', 'fever_pair' => $pair->id, 'label' => 'REFUTES'], ['class' => 'btn btn-danger']) ?>
                <?= Html::a('<i class="far fa-question-circle"></i> Nedostatek informací', ['label/fever', 'fever_pair' => $pair->id, 'label' => 'NOT ENOUGH INFO'], ['class' => 'btn btn-secondary'])  ?>
                <?= Html::a('<i class="fas fa-exclamation-triangle"></i> Nepoužitelný překlad tvrz.', ['label/fever', 'fever_pair' => $pair->id, 'label' => 'MISTRANSLATED'], ['class' => 'btn btn-warning'])  ?>
            </h4>
        </div>
    </div>
</div>
</div>