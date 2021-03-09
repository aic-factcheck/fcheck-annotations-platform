<?php

/* @var $this yii\web\View */
/* @var $t integer */

use yii\bootstrap4\Html;

$this->title = 'Tutoriál';

?>
<div class="container">
    <h1><?=$this->title.($t?' (anotační část - přetočte na 9:20)':'')?></h1>
    <p>Pro osobní rady a konzultace pište Honzovi Drchalovi na <strong>drchajan@fel.cvut.cz</strong> nebo Bertíkovi Ullrichovi na <strong>ullriher@fel.cvut.cz</strong></p>
    <p><?=Html::a('<i class="fas fa-file-pdf"></i> Slidy (PDF)',['/2020_fcheck_anotace.pdf'], ['class'=>'btn btn-primary'])?></p>
    <iframe width="840" height="472" src="https://www.youtube.com/embed/AcarF4Rxexc?start=<?=$t?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <p><?=Html::a('Tutoriál pro minulou verzi od Honzy','https://drive.google.com/file/d/1RubaYwDDwrXjUsSTOhLVqcRZXmPLy6nm/preview')?></p>
</div>