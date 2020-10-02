<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;$this->title = 'Anotační Platforma FCheck TAČR';

?>
<div class="card mb-3" >
    <div class="card-body">
        <h3 class="card-title">WF1: Claim Generation</h3>
        <p class="card-text">The objective of this task is to generate a mixture of true claims and false claims from a source sentence extracted
            from Wikipedia.</p>
        <p class="card-text">In the sandbox mode. Annotations will be saved, but will not form part of the final data set. When done, just close
            the window 👏 </p>
        <?=Html::a('Tutoriál',['claim/tutorial'],['class'=>'btn btn-light'])?>
        <?=Html::a('Zkušební verze (AJ/wiki)', ['claim/annotate','sandbox'=>true],['class'=>'btn btn-secondary'])?>
        <?=Html::a('Ostrá verze (ČJ/čtk)', ['claim/annotate','sandbox'=>false],['class'=>'btn btn-primary'])?>
    </div>
</div>

<div class="card" >
    <div class="card-body">
        <h3 class="card-title">WF2: Claim Labelling</h3>
        <p>The purpose of this task is to identify evidence from a Wikipedia page that can be used to support or refute simple
            factoid sentences called claims.</p>
        <p>In the sandbox mode. Annotations will be saved, but will not form part of the final data set.</p>

        <?=Html::a('Oracle anotace (test pokrytí)',['label/oracle'],['class'=>'btn btn-warning'])?>
        <?=Html::a('Zkušební verze (AJ/wiki)', ['label/sandbox'],['class'=>'btn btn-secondary'])?>
        <?=Html::a('Ostrá verze (ČJ/čtk)', ['label/live'],['class'=>'btn btn-primary'])?>
    </div>
</div>
