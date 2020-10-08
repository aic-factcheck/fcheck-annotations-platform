<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model LabelForm */

use app\models\LabelForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Anotace výroků';
?>
<?php $form = ActiveForm::begin([
    'id' => 'label-form',
]); ?>

    <div class="container-fluid">
        <h1>Anotace správnosti výroku (WF2)</h1>
        <?php if ($model->sandbox) { ?>
            <div>
                <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
                <div style="color:red;">Claims you write will be recorded. But will not form part of the final dataset.
                </div>
            </div>
        <?php } ?>
        <h2 class="float-left mb-3 claim">Výrok: <strong><?= $model->claim->claim ?></strong></h2>
        <p class="text-right float-right">
            <?= Html::activeCheckbox($model, 'flag',['label'=>'<i class="fas fa-flag"></i> Nahlásit','class'=>'flag']); ?>
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['class' => 'btn btn-danger', 'disabled' => true]) ?>
            <?= Html::button('<i class="fas fa-forward"></i> Přeskočit (otevře menu)', ['class' => 'btn btn-light', 'data' => ['toggle' => 'modal', 'target' => '#skip']]) ?>
            <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        </p>
    </div>
    <table class="table table-striped" id="evidence">
        <tr class="table-primary">
            <th class="text-right">Článek: <?= $model->claim->sentence["entity"] ?></th>
            <th class="px-0 text-center">Důkaz#1</th>
        </tr>
        <?php $i = 0;
        foreach ($model->getEntitySentences() as $sentence) { ?>
            <tr>
                <td class="text-right"><?= $sentence ?></td>
                <td class="text-center checkcell">
                    <?= Html::checkbox("evidence[0]['" . $model->claim->sentence['entity'] . "']", false, ["class" => "evidence", "value" => $i++]) ?>
                </td>
            </tr>
        <?php } ?>
        <?php foreach ($model->claim->sentence["dictionary"] as $entity => $text) { ?>
            <tr class="table-info dictionary-item">
                <th class="text-right">Slovníček: <?= $entity ?></th>
                <th class="text-center"><i class="fas fa-caret-down"></i><i class="fas fa-caret-up d-none"></i></th>
            </tr>
            <?php $i = 0;
            foreach (Yii::$app->params['entities'][$entity] as $sentence) { ?>
                <tr class="d-none">
                    <td class="text-right"><?= $sentence ?></td>
                    <td class="text-center checkcell">
                        <?= Html::checkbox("evidence[0]['$entity']", false, ["class" => "evidence", "value" => $i++]) ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr class=" dictionary-item d-none"></tr>
    </table>

    <div class="container-fluid">
        <p class="text-right">
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['class' => 'btn btn-danger', 'disabled' => true]) ?>
            <?= Html::button('<i class="fas fa-forward"></i> Přeskočit (otevře menu)', ['class' => 'btn btn-light', 'data' => ['toggle' => 'modal', 'target' => '#skip']]) ?>
            <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        </p>
    </div>
    <div class="modal fade" id="guidelines" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pokyny</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h1>HTML Ipsum Presents</h1>

                    <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac
                        turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.
                        Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris
                        placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat
                        wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit
                        eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a
                                href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

                    <h2>Header Level 2</h2>

                    <ol>
                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                        <li>Aliquam tincidunt mauris eu risus.</li>
                    </ol>

                    <blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at
                            felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec
                            eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit
                            amet quam. Vivamus pretium ornare est.</p></blockquote>

                    <h3>Header Level 3</h3>

                    <ul>
                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                        <li>Aliquam tincidunt mauris eu risus.</li>
                    </ul>

                    <pre><code>
#header h1 a {
  display: block;
  width: 300px;
  height: 80px;
}
</code></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="skip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Možnosti přeskočení výroku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="far fa-question-circle"></i> Nedostatek informací', ['class' => 'btn btn-info',]) ?><br>
                        Zvolte, pokud zobrazený článek a slovníček pojmů neobsahuje informace dostatečné pro potvrzení nebo vyvrácení výroku. <br>Tento výrok nebude přidělen dalším anotátorům.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="far fa-frown"></i> Nepřeji si anotovat tento výrok', ['class' => 'btn btn-light',]) ?><br>
                        Systém ho přiřadí ostatním anotátorům.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="fas fa-flag"></i> Výrok je nejasný, nesmyslný nebo nelze dokázat', ['class' => 'btn btn-warning',]) ?><br>
                        Výrok bude nahlášen ke kontrole, zda splňuje pokyny z WF1.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="fas fa-flag"></i> Výrok obsahuje překlep nebo drobnou chybu', ['class' => 'btn btn-warning',]) ?><br>
                        Výrok bude zkontrolován a opraven.
                    </p>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>