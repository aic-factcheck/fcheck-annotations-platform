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
                        <li>Pokud nejsou dostatečné ani výchozí odstavce znalostního rámce, můžete znalostní rámec rozšířit kliknutím na odkazy <strong>Zobrazit kontext</strong>.
                        <li>Při úvahách nad platností tvrzení používejte <em>zdravý rozum</em>.</li>
                        <li>Jediným zdrojem informací pro anotaci výroku smí být výchozí článek a texty znalostního rámce. S výjimkou podmíněného potvrzení či vyvrácení (viz níže) <em>je jakékoliv použítí vlastních znalostí zakázáno.</em></li>
                        <li>Texty znalostního rámce vznikly dříve, než text výchozího článku. Platnost tvrzení se tak dokazuje k datu vydání výchozího článku.</li>
                        <li>Důkazů, tedy minimálních skupin odstavců, které potvrzují, či vyvracejí tvrzení můžete zadat více - při zatržení odstavce je automaticky přidán nový sloupec.</li>
                        <li>Pro rozhodnutí anotace zmáčkněte tlačítko <strong>Potvrdit</strong>, <strong>Vyvrátit</strong>, či <strong>Preskočit</strong>.</li>
                        <li>V případě volby <strong>Preskočit</strong> máte více možností:
                            <ul>
                                <li>Pokud výchozí článek ani znalostní rámec neobsahují dostatek informací, zvolte <strong>Nedostatek informací</strong>.</li>
                                <li>Pokud jste si, i přes nedostatek informací, jistí pravdivostí či nepravdivostí výroku, můžete místo <strong>Nedostatek informací</strong> zvolit volbu <strong>Podmíněně potvrdit</strong> nebo <strong>Podmíněně potvrdit</strong>. 
                                    Do příslušného pole <strong>Doplňující tvrzení</strong> pak nezapomeňte vložit text. Interpretace <em>doplňujcího tvrzení</em> je následující: pokud bude ukázána jeho platnost, bude automatcky potvrzeno či vyvráceno i původní tvrzení. 
                                    Pozor: doplňující tvrzení nesmí být parafrází původního tvrzení!</li>
                                <li>V případě nejasného čí chybného tvrzení zvolte <strong>Výrok je nejasný nebo nesmyslný</strong> nebo <strong>Výrok obsahuje překlep nebo drobnou chybu</strong>.</li>
                            </ul>
                        </li>
                    </ol>

                    <h4 class="topmargin ng-scope">Příklady</h4>
                    <h5 class="ng-scope">Podmíněně potvrzené tvrzení</h5>
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> "Sněžka je nejvyšší horou Krkonoš."<br>
                        Nabízené odstavce nejsou pro důkaz tohoto tvrzení dostatečné. Obsahují pouze následující informaci: "Sněžka je nejvyšší horou České republiky."<br>
                        Jste přesvědčen/a o platnosti původního tvrzení (neúplnost znalostního rámce mužete přisuzovat nedokonalosti metody, jíž je vytvářen). Proto zadejte:<br>
                        <strong>Doplňující tvrzení</strong>: "Krkonoše jsou nejvyšší pohoří Česka." a zvolte <strong>Podmíněně potvrdit</strong>.  
                    </div>
                    </p>

                    <h5 class="ng-scope">Časování sloves</h5>
                    <p class="ng-scope">Časování sloves, které nemá vliv na význam ignorujte.</p>

                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Frank Sinatra je muzikant.<br>
                        <strong>Potvrzeno: </strong> ... Je jedním z nejprodávanějších hudebníků na světě, prodal více než 150 milionů nosičů.
                    </div>
                    </p>
                    
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Frank Sinatra je muzikant. <br>
                        <strong>Potvrzeno: </strong> Francis Albert Sinatra (12. prosince 1915 - 14. května 1998) byl americký zpěvák.
                    </div>
                    </p>

                    <h5 class="ng-scope">Entity stejného jména</h5>
                    <p class="ng-scope">Pokud existuje více stejně pojmenovaných entit (například osob nebo míst), a chybí jejich další určení, stačí pro potvrzení platnost tvrzení alespoň pro jednu z nich. 
                        Pro vyvrácení nesmí navíc existovat žádná jiná entita stejného jména, pro kterou by bylo tvrzení pravdivé.</p>
                    
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Antonín Dvořák byl malíř.<br>
                        <strong>Potvrzeno: </strong> Méně známý Antonín Dvořák (narozen 16. prosince 1817 v Němčicích, zemřel 26. dubna 1881 v Praze), byl český malíř a fotograf.
                    </div>
                    </p>
                    
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Antonín Leopold Dvořák byl malíř.<br>
                        <strong>Vyvráceno: </strong> V tomto případě se jedná o známého hudebního skladatele. 
                        Ve znalostním rámci nenajdeme zmínky o tom, že by se významně věnoval malbě (v tomto specifickém případě nenajdeme ve skutečnosti žádné zmínky o tom, že by maloval).
                    </div>
                    </p>

                    <h5 class="ng-scope">Prohlášení</h5>
                    <p class="ng-scope">Prohlášení osoby automaticky nedokazuje tvrzení. Zveřejnění plánů rovněž nemusí být automaticky potvrzením platnosti plánovaného. 
                        V těchto případech je třeba vyjít z kontextu a použít zdravého rozumu. 
                        Na opačných stranách spektra tak může být např. citace spekulující soukromé osoby v kontrastu s mluvčím na tiskové konferenci statistického úřadu.</p>
                    
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Donald Trump byl podruhé zvolen prezidentem.<br>
                        <strong>Nedostatek informací</strong> pro znalostní rámec: "Donald Trump na čtvrteční tiskové konferenci prohlásil, že u voleb obhájil svou prezidentskou pozici." 
                        Pozn.: znalostní rámec v tomto případě bude s vysokou pravděpodobností obsahovat i protikladné informace.
                    </div>
                    </p>
                    
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> Donald Trump prohlásil, že byl podruhé zvolen prezidentem.<br>
                        <strong>Potvrzeno</strong>pro znalostní rámec: "Donald Trump na čtvrteční tiskové konferenci prohlásil, že u voleb obhájil svou prezidentskou pozici."
                    </div>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>