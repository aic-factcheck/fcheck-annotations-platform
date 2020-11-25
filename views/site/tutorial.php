<?php

/* @var $this yii\web\View */
/* @var $t integer */

use yii\bootstrap4\Html;

$this->title = 'Tutoriál';

?>
<div class="container">
    <h1><?=$this->title.($t?' (anotační část)':'')?></h1>
    <p>Pro osobní rady a konzultace pište Honzovi Drchalovi na <strong>drchajan@fel.cvut.cz</strong> nebo Bertíkovi Ullrichovi na <strong>ullriher@fel.cvut.cz</strong></p>
    <p><?=Html::a('<i class="fas fa-file-pdf"></i> Slidy (PDF)',['/2020_fcheck_anotace.pdf'], ['class'=>'btn btn-primary'])?></p>
    <iframe src="https://drive.google.com/file/d/1RubaYwDDwrXjUsSTOhLVqcRZXmPLy6nm/preview?t=<?=$t?>" class="w-100" style="height: 40rem"></iframe>
</div>