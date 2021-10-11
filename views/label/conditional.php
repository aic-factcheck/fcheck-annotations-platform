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
                            Anotace #<?= $label->id ?>, Tvrzení #<?= $label->claim ?> <?=$label->label?>
                            (podmíněný <?= \yii\helpers\Html::tag('strong', $label->label, ['class' => 'badge badge-' . ($label->label == "SUPPORTS" ? "success" : "danger")]) ?>
                            )
                        </h3></div>
                    <div class="card-body">
                        <?= "<h5>„" . $label->claim0->claim . "“ " . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($label->claim0->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                        . "</h5>"; ?>
                        <hr/>
                        <h6>Kontext</h6>
                        <?php
                        $b = false;
                        foreach ($label->evidences as $evidence) {
                            $b = true;
                            echo Html::tag('p', $evidence->paragraph0->text .
                                ' ' . \yii\helpers\Html::tag('small', $evidence->paragraph0->article0->title, ['class' => 'badge badge-success ']) .
                                ' ' . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($evidence->paragraph0->article0->date), ['class' => 'badge badge-secondary '])
                            );
                        }
                        if (!$b) {
                            echo Html::tag('p', $label->claim0->paragraph0->text);
                        }
                        ?>
                        <hr/>
                        <h6>
                            <strong>Podmínka:</strong> „<?= $label->condition ?>“
                        </h6>
                        <h6><strong>Návrhy na rozšíření podmínky</strong>:</h6>
                        <table class="table table-striped">
                            <?php
                            foreach ($label->knowledge as $knowledge) {
                                $paragraph = $knowledge->knowledge0;
                                ?>
                                <tr>
                                    <td><?= Html::checkbox('evidence[' . $label->id . '][]', false, ['value' => $paragraph->id]) ?></td>
                                    <td><p><?= $paragraph->text?> <small class="badge badge-primary "><?=$paragraph->article0->title?></small>
                                            <small class="badge badge-secondary "><?=Yii::$app->formatter->asDatetime($paragraph->article0->date)?></small></p></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <hr/>
                        <div class="text-right">
                            <label class="mr-3"><strong>Poznámka:</strong>
                                <?= Html::input('text', 'note[' . $label->id . ']', $label->note) ?>
                            </label>
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