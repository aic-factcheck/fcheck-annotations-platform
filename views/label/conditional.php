<?php

/* @var $this yii\web\View */

/* @var $labels array */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Podmíněné anotace';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php ActiveForm::begin(['layout' => 'horizontal',
        'class' => 'form-horizontal',]) ?>
    <p>
        <?= Html::button('<i class="fas fa-check"></i>  Uložit úpravy', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </p>
    <?php
    foreach ($labels as $label) {
        try {
            if ($label->claim0 != null) {
                ?>
                <div class="card bg-light mb-3">
                    <div class="card-header"><h3>
                            Tvrzení #<?= $label->claim ?> (podmíněný <?= \yii\helpers\Html::tag('strong', $label->label, ['class' => 'badge badge-'.($label->label == "SUPPORTS"?"success":"danger")])?>)
                        </h3></div>
                    <div class="card-body">
                        <h6>Tvrzení</h6>
                        <?= "<h5>„" . $label->claim0->claim . "“ " . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($label->claim0->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                        . "</h5>"; ?>
                        <h6>Kontext</h6>
                        <?php
                        $b = false;
                        foreach ($label->evidences as $evidence) {
                            $b = true;
                            echo Html::tag('p', $evidence->paragraph0->text .
                                ' ' . \yii\helpers\Html::tag('small', $evidence->paragraph0->article0->title, ['class' => 'badge badge-primary ']) .
                                ' ' . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($evidence->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                            );
                        }
                        if (!$b) {
                            echo Html::tag('p', $label->claim0->paragraph0->text);
                        }
                        ?>
                        <div class="card bg-white mb-3">
                            <div class="card-body">
                                <h6>
                                    <strong>Podmínka:</strong> „<?= $label->condition ?>“
                                </h6>
                                <h6><strong>Návrhy na rozšíření podmínky</strong>:</h6>
                                <table class="table table-striped">
                                    <tr><td><?=Html::checkbox('evidence['.$label->id.'][]',false,['value'=>1])?></td><td><p>Lidovci vyvolají jednání s cílem odvolat předsedu SPD Tomia Okamuru z funkce místopředsedy Sněmovny kvůli jeho výrokům o protektorátním koncentračním táboře v Letech u Písku . Záměr podpořili i Starostové a TOP 09 , otevření jsou k němu i ODS a někteří zástupci ČSSD . Ministr spravedlnosti v demisi Robert Pelikán ( ANO ) vyjádřil nad Okamurovými výroky znepokojení a vyzval ho , aby s nimi přestal . Okamura uvedl , že jeho hnutí nezpochybňuje utrpení lidí v letském táboře . Kritiku , které on i poslanec SPD Miloslav Rozner čelí , označil za součást kampaně vedené s cílem zabránit přímé i nepřímé účasti hnutí na sestavování vlády . <small class="badge badge-primary ">Zpravodajský souhrn ČTK</small> <small class="badge badge-secondary ">07.02.2018 02:04</small></p></td></tr>
                                    <tr><td><?=Html::checkbox('evidence['.$label->id.'][]',false,['value'=>1])?></td><td>
                                            <p>Brusel/Londýn 2. února ( zpravodaj ČTK ) - Návrh dohody , která má nově definovat vztah Británie a Evropské unie , dnes zveřejnil stálý předseda Evropské rady Donald Tusk . Dokument , kterým se ještě tento měsíc bude zabývat summit EU , je podle Tuska dobrým základem ke kompromisu , `` unijní prezident '' ale ještě čeká tvrdá jednání . Britský premiér David Cameron na twitteru krátce po zveřejnění návrhu reagoval poznámkou , že ačkoliv dokumenty ukazují pokrok ve všech čtyřech oblastech , o kterých chtěl Londýn jednat , je třeba ještě další práce . <small class="badge badge-primary ">Tusk zveřejnil návrh dohody o změnách vztahů Británie a EU</small> <small class="badge badge-secondary ">02.02.2016 02:50</small></p>
                                        </td></tr>
                                    <tr><td><?=Html::checkbox('evidence['.$label->id.'][]',false,['value'=>1])?></td><td>
                                            <p>Žulawského filmy jsou plné drastických scén i nahoty . Hned jeho druhý celovečerní hraný film The Devil ( Ďábel ) z roku 1972 byl v Polsku zakázán a filmař se přestěhoval do Francie , kde v 50. letech studoval . Velké diskuse v Polsku vyvolal už jeho debutový film Třetí část noci ( 1971 ) , rovněž drama s hororovými prvky v historických kulisách . <small class="badge badge-primary ">Zemřel Andrzej Žulawski , polský režisér i exmanžel Marceauové</small> <small class="badge badge-secondary ">17.02.2016 13:18</small></p>
                                        </td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="text-right">
                            <label class="mr-3"><strong>Nový label:</strong>
                                <?= Html::dropDownList('label[' . $label->id . ']', null, Label::LABELS_WITH_VOID) ?>
                            </label>
                            <label><strong>Soft-smazat podmíněnou anotaci:</strong>
                                <?= Html::checkbox('delete[' . $label->id . ']', false) ?>
                            </label>
                        </div>
                    </div>
                </div>
                <?php
            }
        } catch (\yii\base\ErrorException $e) {
            echo $e;
            continue;
        }
    }
    ?>
    <p>
        <?= Html::button('<i class="fas fa-check"></i>  Uložit úpravy', ['class' => 'btn btn-primary']) ?>
    </p>
    <?php ActiveForm::end() ?>
</div>