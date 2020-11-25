<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'Anotační Platforma FCheck TAČR';

?>
<div class="container">
    <div class="card my-3">
        <div class="card-body">
            <h3 class="card-title">Ú<sub>0</sub>: Předvýběr zdrojových odstavců</h3>
            <p class="card-text">Cílem úkolu je identifikovat v korpusu ČTK odstavce, které je možno použít jako základ pro extrakci tvrzení v úkolu Ú<sub>1</sub>.</p>
            <p class="card-text"><strong>Úkol není povinný</strong> pro studenty FSV UK 🙂. Byl vypracován týmem AIC. Dobrovolníci mohou vyzkoušet.</p>
            <?= Html::a('<i class="fas fa-highlighter"></i> Vybírat odstavce', ['ctk/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h3 class="card-title">Ú<sub>1</sub>: Tvorba tvrzení</h3>
            <p class="card-text">Cílem úkolu je vytvořit množství pravdivých a nepravdivých tvrzení extrakcí z
                nabízených vět z korpusu tiskových zpráv ČTK.
            <p class="card-text"> Po skončení jednodušše zavřete okno prohlížeče. 👏</p>

            <?= Html::a('<i class="fab fa-youtube"></i> Tutoriál', ['claim/tutorial'], ['class' => 'btn btn-light disabled', 'disabled' => true]) ?>
            <?= Html::a('<i class="fas fa-asterisk"></i> Začít tvořit výroky', ['claim/annotate', 'sandbox' => false], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h3 class="card-title">Ú<sub>2</sub>: Anotace faktické správnosti tvrzení</h3>
            <p>Cílem úkolu je identifikovat důkazy z korpusu tiskových zpráv ČTK, které lze použít k potvrzení nebo
                vyvrácení jednoduchých faktoidních tvrzení.</p>
            <p><strong>Anotace vlastních tvrzení</strong> slouží jako <em>referenční anotace</em>. Doporučujeme se jí věnovat ve chvíli, kdy máte svá tvrzení v živé paměti po Ú1. </p>

            <?= Html::a('<i class="fas fa-balance-scale-left"></i> Anotovat vlastní tvrzení', ['label/', 'sandbox' => 0, 'oracle' => 1], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('<i class="fas fa-balance-scale-right"></i> Anotovat cizí tvrzení', ['label/', 'sandbox' => 0, 'oracle' => 0], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>