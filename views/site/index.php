<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'Anotační Platforma FCheck TAČR';

?>
<div class="container">
    <div class="card my-3">
        <div class="card-body">
            <h3 class="card-title">WF0: Nalezení kandidátních vět</h3>
            <p class="card-text">Cílem úkolu je identifikovat v korpusu ČTK shluky vět, které je možno použít pro úkol WF1</p>
            <?= Html::a('Ostrá verze', ['candidate/'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h3 class="card-title">WF1: Tvorba výroků</h3>
            <p class="card-text">Cílem úkolu je vygenerovat množství pravdivých a nepravdivých výroků extrakcí z
                nabízených vět z korpusu tiskových zpráv ČTK.
            <p class="card-text"><strong>Zkušební verze</strong> umožňuje nácvik úkolu na lépe fakticky strukturovaných
                datech z Wikipedie, data budou uložena ale nepoužita. </p>
            <p class="card-text"> Po skončení jednodušše zavřete okno prohlížeče. 👏</p>

            <?= Html::a('Tutoriál', ['claim/tutorial'], ['class' => 'btn btn-light']) ?>
            <?= Html::a('Zkušební verze (AJ/wiki)', ['claim/annotate', 'sandbox' => true], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Ostrá verze (ČJ/čtk)', ['claim/annotate', 'sandbox' => false], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title">WF2: Anotace faktické správnosti výroků</h3>
            <p>Cílem úkolu je identifikovat důkazy z korpusu tiskových zpráv ČTK, které lze použít k potvrzení nebo
                vyvrácení jednoduchých faktických výroků.</p>
            <p><strong>Zkušební verze</strong> umožňuje nácvik úkolu na lépe fakticky strukturovaných datech z
                Wikipedie, data budou uložena ale nepoužita. </p>
            <p><strong>Oracle anotace</strong> slouží <em>superanotátorům</em> k vyplnění. </p>

            <?= Html::a('Oracle anotace (test pokrytí)', ['label/', 'sandbox' => 0, 'oracle' => 1], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Zkušební verze (AJ/wiki)', ['label/', 'sandbox' => 1, 'oracle' => 0], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Ostrá verze (ČJ/čtk)', ['label/', 'sandbox' => 0, 'oracle' => 0], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>