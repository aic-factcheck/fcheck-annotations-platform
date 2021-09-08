<?php

/* @var $this yii\web\View */

/* @var $misclassifications array */
/* @var $image string */

/* @var $batch string */

use app\models\Label;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Rozpory modelu a anotací (Dávka' . $batch . ')';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <?php ActiveForm::begin(['layout' => 'horizontal',
        'class' => 'form-horizontal',]) ?>
    <p>
        <?= Html::button('<i class="fas fa-check"></i>  Uložit úpravy', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </p>
    <p>
        <img src="<?= \yii\helpers\Url::to([$image]) ?>" alt="Matice zmatení" class="w-100">
    </p>
    <?php
    foreach ($misclassifications as $misclassification) {
        try {
            foreach ($misclassification["labels"] as $label) {
                ?>
                <div class="card bg-light mb-3">
                    <div class="card-header"><h3>
                            Tvrzení #<?= $misclassification['claim'] ?>
                            (👨: <?= $misclassification['claim_']->getMajorityLabel() ?>,
                            🤖: <?= $misclassification["prediction"] ?>, <?= $misclassification['certainty'] ?>%)
                        </h3></div>
                    <div class="card-body">
                        <h6>Tvrzení</h6>
                        <?= "<h5>„" . $misclassification['claim_']->claim . "“</h5><br/>".\yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($misclassification['claim_']->claim->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                        ; ?>
                        <h6>Kontext</h6>
                        <?php
                        $b = false;
                        foreach ($label->evidences as $evidence) {
                            $b = true;
                            echo Html::tag('p', $evidence->paragraph0->text .
                                ' ' . \yii\helpers\Html::tag('small', $evidence->paragraph0->article0->title, ['class' => 'badge badge-primary ']).
                                ' ' . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($evidence->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                            );
                        }
                        if (!$b) {
                            echo Html::tag('p', $misclassification["claim_"]->paragraph0->text);
                        }
                        ?>
                        <p></p>
                    </div>
                    <div class="card-footer text-right">
                        <label class="mr-3"><strong>Poznámka:</strong>
                            <?= Html::input('text', 'note[' . $label->id . ']', $label->note) ?>
                        </label>
                        <label class="mr-3"><strong>Nový label:</strong>
                            <?= Html::dropDownList('label[' . $label->id . ']', null, Label::LABELS_WITH_VOID) ?>
                        </label>
                        <label><strong>Soft-smazat anotaci:</strong>
                            <?= Html::checkbox('delete[' . $label->id . ']', false) ?>
                        </label>
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