<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model LabelForm */

use app\helpers\Entity;
use app\helpers\Helper;
use app\models\LabelForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

Helper::setEntities(\yii\helpers\ArrayHelper::merge($model->claim->ners,$model->claim->paragraph0->ners));
$this->title = 'Anotace výroků';
//die(json_encode(Helper::$entities));
?>
<?php $form = ActiveForm::begin([
    'id' => 'label-form',
]); ?>

    <div class="container-fluid">
        <h1>Anotace správnosti výroku (Ú<sub>2</sub>)</h1>
        <?php if ($model->sandbox) { ?>
            <div>
                <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
                <div style="color:red;">Claims you write will be recorded. But will not form part of the final dataset.
                </div>
            </div>
        <?php } ?>
        <h2 class="float-left mb-3 claim">Výrok: <strong><?= $model->claim->claim ?></strong></h2>
        <p class="text-right float-right">
            <?= Html::activeHiddenInput($model, 'load', ['value' => true]); ?>
            <?= Html::activeCheckbox($model, 'flag', ['label' => '<i class="fas fa-flag"></i> Nahlásit', 'id' => 'flag']); ?>
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['name' => 'label', 'value' => 'REFUTES', 'class' => 'btn btn-danger', 'disabled' => true]) ?>
            <?= Html::button('<i class="fas fa-forward"></i> Přeskočit (otevře menu)', ['class' => 'btn btn-light', 'data' => ['toggle' => 'modal', 'target' => '#skip']]) ?>
            <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        </p>
    </div>
    <table class="table table-striped" id="evidence">
        <tr class="table-primary">
            <th class="text-right">Článek: <?= $model->claim->paragraph0->article0->get('title').' '. \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($model->claim->paragraph0->article0->date),['class'=>'badge badge-secondary ']) ?></th>
            <th class="px-0 text-center">Důkaz#1</th>
        </tr>
        <?php $i = 0;
        foreach ($model->claim->paragraph0->article0->paragraphs as $paragraph) { ?>
            <tr>
                <td class="text-right"><?= $paragraph->get('text') ?></td>
                <td class="text-center checkcell">
                    <?= Html::checkbox("evidence[0][]", false, ["class" => "evidence", "value" => $paragraph->id]) ?>
                </td>
            </tr>
        <?php } ?>
        <?php foreach ($model->claim->knowledge as $paragraph) { ?>
            <tr class="table-info dictionary-item">
                <th class="text-right">Znalostní rámec: <?= $paragraph->article0->get('title') .' '. \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($paragraph->article0->date),['class'=>'badge badge-secondary '])?></th>
                <th class="text-center"><i class="fas fa-caret-down"></i><i class="fas fa-caret-up d-none"></i></th>
            </tr>
            <?php $i = 0;
            foreach ($paragraph->article0->paragraphs as $paragraph_) { ?>
                <tr class="d-none <?= $paragraph_->id != $paragraph->id ? "$paragraph->id-context":'text-strong'?>">
                    <td class="text-right"><?= $paragraph_->get('text') ?></td>
                    <td class="text-center checkcell">
                        <?= Html::checkbox("evidence[0][]", false, ["class" => "evidence", "value" => $paragraph_->id]) ?>
                    </td>
                </tr>
            <?php } ?>
            <tr class="d-none" data-show=".<?=$paragraph->id?>-context" data-alt='<td class="text-right font-weight-bold">Skrýt kontext &laquo;</td><td colspan="999"></td>'>
               <td class="text-right font-weight-bold expand-context">Zobrazit kontext &raquo;</td><td colspan="999"></td>
            </tr>
        <?php } ?>
        <tr class=" dictionary-item d-none"></tr>
    </table>

    <div class="container-fluid">
        <p class="text-right">
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['name' => 'label', 'value' => 'REFUTES', 'class' => 'btn btn-danger', 'disabled' => true]) ?>
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
                    <h4 class="topmargin ng-scope">Jak anotovat?</h4>

                    <ol class="gul ng-scope">
                        <li>Přečtěte si tvrzení a pokuste se mu porozumět.</li>
                        <li>Projděte si odstavce výchozího článku a zatrhněte ty (a pouze ony), které vám umožní tvrzení potvrdit či vyvrátit.</li>
                        <li>Skupina takto označených odstavců tvoří <em>důkaz</em>.</li>
                        <li>Pokud odstavce původního článku neobsahují dostatek informací k vytvoření důkazu, rozbalte a případně zatrhněte vhodné odstavce <em>znalostního rámce</em> níže na stránce.</li>
                        <li>Pokud nejsou vhodné ani výchozí odstavce znalostního rámce, můžete znalostní rámec rozšířit odkazem <strong>Zobrazit kontext</strong>.
                        <li>Při úvahách nad platností tvrzení používejte <strong>zdravý rozum</strong>.</li>
                        <li>Jediným zdrojem informací pro anotaci výroku smí být výchozí článek a texty znalostního rámce. S výjimkou podmíněného potvrzení či vyvrácení (viz níže) <em>je jakékoliv použítí vlastních znalostí je zakázáno.</em></li>
                        <li>Texty znalostního rámce vznikly dříve, než text výchozího článku. Platnost tvrzení se tak dokazuje k datu vydání výchozího článku.</li>
                        <li>Důkazů, tedy minimálních skupin odstavců, které potvrzují, či vyvracejí tvrzení můžete zadat více - při zatržení odstavce je automaticky přidán nový sloupec.</li>
                        <li>Pro rozhodnutí anotace zmáčkněte tlačítko <strong>Potvrdit</strong>, <strong>Vyvrátit</strong>, či <strong>Preskočit</strong>.</li>
                        <li>V případě volby <strong>Preskočit</strong> máte více možností:
                            <ul>
                                <li>Pokud výchozí článek a znalostní rámec neobsahují dostatek informací, zvolte <strong>Nedostatek informací.</strong></li>
                                <li>PRIDAT INFO O PODMINENE ANOTACI, sem muze vstoupit external knowledge</li>
                                <li>Význam posledních dvou tlačítek je zřejmý: <strong>Výrok je nejasný, nesmyslný nebo nelze dokázat.</strong>, <strong>Výrok obsahuje překlep nebo drobnou chybu.</strong></li>
                            </ul>
                        </li>
                        <li>TODO CASOVE paradoxy, platnost v cas napsani clanku? - asi pouze pokud je tam i casove urceni. "Dvojčata jsou nejvyššími budovami v New Yorku."</li>
                    </ol>


                    <h4 class="topmargin ng-scope">Příklady</h4>
                    <p class="ng-scope">TODO CASOVE "paradoxy,"" platnost v cas napsani clanku? - asi pouze pokud je tam i casove urceni.</p>
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> "Dvojčata jsou nejvyššími budovami v New Yorku."<br>
                        <strong>Supported: </strong> He is one of the best-selling music artists of all time, having
                        sold more than 150 million records worldwide.
                    </div>

                    <p class="ng-scope">TODO The difference in verb tenses that do not affect the meaning should be ignored.</p>
                    <div class="ebox ng-scope">
                        <strong>Claim: </strong> Frank Sinatra is a musician<br>
                        <strong>Supported: </strong> He is one of the best-selling music artists of all time, having
                        sold more than 150 million records worldwide.
                    </div>

                    <div class="ebox ng-scope">
                        <strong>Claim: </strong> Frank Sinatra is a musician <br>
                        <strong>Supported: </strong> Francis Albert Sinatra (/sɪˈnɑːtrə/; Italian: [siˈnaːtra]; December
                        12, 1915 – May 14, 1998) was an American singer
                    </div>

                    <p class="ng-scope">Pokud existuje více stejně pojmenovaných entit (například osob, míst), stačí pro potvrzení platnost tvrzení alespoň u jedné z nich. 
                        Pro vyvrácení nesmí navíc existovat žádná entita, pro kterou by bylo tvrzení pravdivé.</p>

                    <p class="ng-scope">TODO Prohlášení osob (Trump prohlasil, ze byl zvolen prezidentem).</p>

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
                        <?= Html::submitButton('<i class="far fa-question-circle"></i> Nedostatek informací', ['class' => 'btn btn-info', 'value' => 'NOT ENOUGH INFO', 'name' => 'label']) ?>
                        <br>
                        Zvolte, pokud zobrazený článek a znalostní rámec neobsahují informace dostatečné pro potvrzení
                        nebo vyvrácení výroku. <br>Tento výrok nebude přidělen dalším anotátorům.
                    </p>
                    <p class="ng-scope">
                        <?=Html::textInput("condition",null,['placeholder'=>'Tvrzení s chybějící znalostí'])?><br>
                        <?= Html::submitButton('<i class="far fa-question-circle"></i> Podmíněně potvrdit', ['class' => 'btn btn-info', 'value' => 'NOT ENOUGH INFO', 'name' => 'label']) ?>
                        <?= Html::submitButton('<i class="far fa-question-circle"></i> Podmíněně vyvrátit', ['class' => 'btn btn-info', 'value' => 'NOT ENOUGH INFO', 'name' => 'label']) ?>
                        <br>
                        Zvolte, pokud zobrazený článek a znalostní rámec neobsahují informace dostatečné pro potvrzení
                        nebo vyvrácení výroku, ale znáte tvrzení, které, je-li pravdivé, várok potvrzuje, nebo vyvrací.
                        <br>Tento výrok nebude přidělen dalším anotátorům.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="far fa-frown"></i> Nepřeji si anotovat tento výrok', ['class' => 'btn btn-light',]) ?>
                        <br>
                        Systém ho přiřadí ostatním anotátorům.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="fas fa-flag"></i> Výrok je nejasný, nesmyslný nebo nelze dokázat', ['class' => 'btn btn-warning autoflag',]) ?>
                        <br>
                        Výrok bude nahlášen ke kontrole, zda splňuje pokyny z Ú<sub>1</sub>.
                    </p>
                    <hr class="ng-scope">
                    <p class="ng-scope">
                        <?= Html::submitButton('<i class="fas fa-flag"></i> Výrok obsahuje překlep nebo drobnou chybu', ['class' => 'btn btn-warning autoflag',]) ?>
                        <br>
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