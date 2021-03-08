<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */
/* @var $oracle bool */

/* @var $model LabelForm */

use app\helpers\Entity;
use app\helpers\Helper;
use app\models\LabelForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

Helper::setEntities(ArrayHelper::merge($model->claim->ners, $model->claim->paragraph0->ners));
$this->title = 'Anotace tvrzení';
//die(json_encode(Helper::$entities));
?>
<?php $form = ActiveForm::begin([
    'id' => 'label-form',
]); ?>

    <div class="container">
        <h1>Anotace správnosti <?= $oracle ? 'vlastního' : 'cizího' ?> tvrzení (Ú<sub>2</sub><?= $oracle ? 'a' : 'b' ?>)
        </h1>
        <div class="card bg-primary text-white mb-3 zdrojovy-vyrok">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><h4 class="card-title">Tvrzení</h4></div>
                    <div class="col-md-9">
                        <div class="card bg-white text-black">
                            <div class="card-body">
                                <h5 class="card-title d-inline"><?= $model->claim->claim ?> </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-right text-right">
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['name' => 'label', 'value' => 'REFUTES', 'class' => 'btn btn-danger', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="far fa-question-circle"></i> Nedostatek informací', ['class' => 'btn btn-secondary', 'value' => 'NOT ENOUGH INFO', 'name' => 'label']) ?>
            <?= Html::a('<i class="fas fa-forward"></i> Přeskočit', ['label/index', 'sandbox' => 0, 'oracle' => $oracle], ['class' => 'btn btn-light', /*'data' => ['toggle' => 'modal', 'target' => '#skip']*/]) ?>
            <?= Html::button('<i class="fas fa-flag"></i> Nahlásit chybu', ['class' => 'btn btn-warning', 'data' => ['toggle' => 'modal', 'target' => '#skip']]) ?>
            <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        </p>
    </div>
    <div class="container">
        <div class="alert  mt-3 alert-warning alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Zlatá pravidla anotace</h4>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <li>Před první anotací si, prosím,
                    přečtěte <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info btn-sm', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
                </li>
                <li>
                    Pozor na <strong>nevýlučnost jevů</strong>, zejména u anotací typu <strong>vyvrátit</strong>.
                    <em>Např. "v Písku se staví kino" nevyvrací "v Pisku se staví divadlo"</em>.
                </li>
                <li>Pokud důkazy samy o sobě nestačí, <strong>prosíme, uveďte chybějící informace jako
                        podmínku anotace</strong> <i class="fas fa-arrow-down"></i> <br/>
                </li>
            </ul>
        </div>

        <div class="card bg-default mb-3 podminka w-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><h5 class="card-title">Podmínka anotace</h5></div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'condition')->textInput(['placeholder' => ' Sem můžete napsat informaci chybějící k úplnosti důkazu.'])->label(false)->hint('Např. "Lidé narození 12. srpna jsou ve znamení lva." nebo "Rakousko je v Evropě.".') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-light mb-3">
            <div class="card-body w-100">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="card-title">Důkazy potvrzující/vyvracející tvrzení</h4>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="evidence">
                                    <tr class=" table-primary">
                                        <th class="text-left">
                                            Zdrojový
                                            článek: <?= $model->claim->paragraph0->article0->get('title') . ' ' . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($model->claim->paragraph0->article0->date), ['class' => 'badge badge-secondary ']) ?></th>
                                        <th class="px-0 text-center">Důkaz#1</th>
                                    </tr>
                                    <?php $i = 0;
                                    foreach ($model->claim->paragraph0->article0->paragraphs as $paragraph) { ?>
                                        <tr>
                                            <td class="text-left"><?= $paragraph->get('text') ?></td>
                                            <td class="text-center checkcell">
                                                <?= Html::checkbox("evidence[0][]", false, ["class" => "evidence", "value" => $paragraph->id]) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php foreach ($model->claim->knowledge as $paragraph) { ?>
                                        <tr class="table-info dictionary-item bg-info">
                                            <th class="text-left">Znalostní
                                                rámec: <?= $paragraph->article0->get('title') . ' ' . \yii\helpers\Html::tag('small', Yii::$app->formatter->asDatetime($paragraph->article0->date), ['class' => 'badge badge-secondary ']) ?></th>
                                            <th class="text-center"><i class="fas fa-caret-down"></i><i
                                                        class="fas fa-caret-up d-none"></i></th>
                                        </tr>
                                        <?php $i = 0;
                                        foreach ($paragraph->article0->paragraphs as $paragraph_) { ?>
                                            <tr class="d-none <?= $paragraph_->id != $paragraph->id ? "$paragraph->id-context" : 'text-strong' ?>">
                                                <td class="text-left"><?= $paragraph_->get('text') ?></td>
                                                <td class="text-center checkcell">
                                                    <?= Html::checkbox("evidence[0][]", false, ["class" => "evidence", "value" => $paragraph_->id]) ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="d-none" data-show=".<?= $paragraph->id ?>-context"
                                            data-alt='<td class="text-right font-weight-bold">Skrýt kontext &laquo;</td><td colspan="999"></td>'>
                                            <td class="text-right font-weight-bold expand-context">Zobrazit kontext
                                                &raquo;
                                            </td>
                                            <td colspan="999"></td>
                                        </tr>
                                    <?php } ?>
                                    <tr class=" dictionary-item d-none"></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-right text-right">
            <?= Html::submitButton('<i class="fas fa-check"></i> Potvrdit', ['name' => 'label', 'value' => 'SUPPORTS', 'class' => 'btn btn-success', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="fas fa-times"></i> Vyvrátit', ['name' => 'label', 'value' => 'REFUTES', 'class' => 'btn btn-danger', 'disabled' => true]) ?>
            <?= Html::submitButton('<i class="far fa-question-circle"></i> Nedostatek informací', ['class' => 'btn btn-secondary', 'value' => 'NOT ENOUGH INFO', 'name' => 'label']) ?>
            <?= Html::a('<i class="fas fa-forward"></i> Přeskočit', ['label/index', 'sandbox' => 0, 'oracle' => $oracle], ['class' => 'btn btn-light', /*'data' => ['toggle' => 'modal', 'target' => '#skip']*/]) ?>
            <?= Html::button('<i class="fas fa-flag"></i> Nahlásit chybu', ['class' => 'btn btn-warning', 'data' => ['toggle' => 'modal', 'target' => '#skip']]) ?>
            <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        </p>
    </div>
    <div class="modal fade" id="guidelines" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-info"></i> Pokyny</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="topmargin ng-scope">Jak anotovat?</h4>

                    <ol class="gul ng-scope">
                        <li>Přečtěte si tvrzení a pokuste se mu porozumět.</li>
                        <li>Projděte si odstavce výchozího článku a zatrhněte ty, které vám umožní tvrzení potvrdit, či
                            vyvrátit.
                        </li>
                        <li>Skupina takto označených odstavců tvoří <em>důkaz</em>.</li>
                        <li>Důkaz tvoří minimální počet odstavců, které potvrzují nebo vyvrací všechny informace
                            vyplývající z tvrzení.
                        </li>
                        <li>Pokud odstavce původního článku neobsahují dostatek informací k vytvoření důkazu, rozbalte a
                            případně zatrhněte vhodné odstavce <em>znalostního rámce</em> níže na stránce.
                        </li>
                        <li>Pokud nejsou dostatečné ani výchozí odstavce znalostního rámce, můžete znalostní rámec
                            rozšířit kliknutím na odkazy <strong>Zobrazit kontext</strong>.
                        <li>Při úvahách nad platností tvrzení používejte <em>zdravý rozum</em>.</li>
                        <LI>Správnost výroků dokazujte vždy k datu vydání <em>zdrojového článku</em>:
                            <strong><i class="fas fa-history"></i> <?= Yii::$app->formatter->asDate($model->claim->paragraph0->article0->date) ?></strong>.
                            Tedy relativní určení času ("včera", "letos",...) a v čase pomíjivé jevy ("nejteplejší
                            léto",...) fact-checkujte z pohledu tohoto dne.
                        </LI>
                        <li>Jediným zdrojem informací pro anotaci tvrzení smí být zdrojový článek a texty znalostního
                            rámce. S výjimkou podmíněného potvrzení či vyvrácení (viz níže) <em>je jakékoliv použítí
                                vlastních znalostí zakázáno.</em></li>
                        <li>Důkazů, tedy minimálních skupin odstavců, které potvrzují, či vyvracejí tvrzení můžete zadat
                            více - při zatržení odstavce je automaticky přidán nový sloupec.
                        </li>
                        <li>Pro rozhodnutí anotace zmáčkněte tlačítko
                            <strong class="badge badge-success"> <i class="fas fa-check"></i> Potvrdit</strong>,
                            <strong class="badge badge-danger"><i class="fas fa-times"></i> Vyvrátit</strong>, nebo
                            <strong class="badge badge-dark"><i class="far fa-question-circle"></i> Nedostatek informací</strong>.
                        </li>
                        <li>Pro změnu tvrzení vybraného k anotaci zvolte
                            <strong class="badge badge-default"> <i class="fas fa-forward"></i> Přeskočit</strong>, nové tvrzení Vám bude přiřazeno náhodně.
                        </li>
                        <li>Pokud ve tvrzení spatřujete <strong>překlep</strong>, <strong>gramatickou chybu</strong>, nebo <strong>porušení zásad tvorby výroku</strong>, prosím, využijte možnost
                            <strong class="badge badge-warning"><i class="far fa-flag"></i> Nahlásit chybu</strong> - tvrzení bude vyřazeno z anotací a odesláno k našemu přehodnocení/opravě.</li>
                        <!--li>V případě volby <strong>Preskočit</strong> máte více možností:
                            <ul>
                                <li>Pokud výchozí článek ani znalostní rámec neobsahují dostatek informací, zvolte
                                    <strong>Nedostatek informací</strong>.
                                </li>
                                <li>Pokud jste si, i přes nedostatek informací, jistí pravdivostí či nepravdivostí
                                    tvrzení, můžete místo <strong>Nedostatek informací</strong> zvolit volbu <strong>Podmíněně
                                        potvrdit</strong> nebo <strong>Podmíněně vyvrátit</strong>.
                                    Do příslušného pole <strong>Doplňující tvrzení</strong> pak musíte vložit text.
                                    Interpretace <em>doplňujcího tvrzení</em> je následující: pokud bude ukázána jeho
                                    platnost, bude automatcky potvrzeno či vyvráceno i původní tvrzení.
                                    Pozor: doplňující tvrzení nesmí být parafrází původního tvrzení!
                                </li>
                                <li>V případě nejasného či chybného tvrzení zvolte <strong>Tvrzení je nejasné nebo
                                        nesmyslné</strong> nebo <strong>Tvrzení obsahuje překlep nebo drobnou
                                        chybu</strong>.
                                </li>
                            </ul>
                        </li-->
                    </ol>

                    <h4 class="topmargin ng-scope">Příklady</h4>
                    <h5 class="ng-scope">Podmíněně potvrzené tvrzení</h5>
                    <p class="ng-scope">
                    <div class="ebox ng-scope">
                        <strong>Tvrzení: </strong> "Sněžka je nejvyšší horou Krkonoš."<br>
                        Nabízené odstavce nejsou pro důkaz tohoto tvrzení dostatečné. Obsahují pouze následující
                        informaci: "Sněžka je nejvyšší horou České republiky."<br>
                        Jste přesvědčen/a o platnosti původního tvrzení (neúplnost znalostního rámce mužete přisuzovat
                        nedokonalosti metody, jíž je vytvářen). Proto zadejte:<br>
                        <strong>Doplňující tvrzení</strong>: "Krkonoše jsou nejvyšší pohoří Česka." a zvolte <strong>Podmíněně
                            potvrdit</strong>.
                    </div>
                    </p>

                    <h5 class="ng-scope">Časování sloves</h5>
                    <p class="ng-scope">Časování sloves, které nemá vliv na význam ignorujte.</p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Frank Sinatra je muzikant."<br>
                        <strong>Potvrzeno</strong> čím: "... Je jedním z nejprodávanějších hudebníků na světě, prodal
                        více než 150 milionů nosičů."
                    </p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Frank Sinatra je muzikant." <br>
                        <strong>Potvrzeno</strong> čím: "Francis Albert Sinatra (12. prosince 1915 - 14. května 1998)
                        byl americký zpěvák."
                    </p>

                    <h5 class="ng-scope">Entity stejného jména</h5>
                    <p class="ng-scope">Může se stát, že existuje více entit stejného názvu (například osoby nebo místa)
                        a tvrzení ji dostatečně nespecifikuje (např. datem narození osoby). V tomto případě, hledejte
                        potvrzení pro jakoukoliv z těchto entit.</p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Antonín Dvořák byl malíř."<br>
                        <strong>Potvrzeno</strong> čím: "Méně známý Antonín Dvořák (narozen 16. prosince 1817 v
                        Němčicích, zemřel 26. dubna 1881 v Praze), byl český malíř a fotograf."
                    </p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Antonín Leopold Dvořák byl malíř."<br>
                        <strong>Vyvráceno</strong><br>
                        V tomto případě se jedná o známého hudebního skladatele.
                        Ve zdrojovém článku ani znalostním rámci nenajdeme zmínky o tom, že by se významně věnoval malbě
                        (v tomto specifickém případě nenajdeme ve skutečnosti žádné zmínky o tom, že by maloval).
                    </p>

                    <h5 class="ng-scope">Prohlášení</h5>
                    <p class="ng-scope">Prohlášení osoby automaticky nedokazuje tvrzení. Zveřejnění plánů rovněž
                        automaticky nepotvrzuje, že cíle těchto plánů byly dosaženy.
                        V těchto případech je třeba vyjít z kontextu a použít zdravého rozumu.
                        Na opačných stranách spektra tak může být např. citace spekulující soukromé osoby v kontrastu s
                        mluvčím na tiskové konferenci statistického úřadu.</p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Donald Trump byl podruhé zvolen prezidentem."<br>
                        <strong>Nedostatek informací</strong>: "Donald Trump prohlásil, že u voleb obhájil svou
                        prezidentskou pozici."<br>
                        Poznámka: znalostní rámec v tomto případě bude s vysokou pravděpodobností obsahovat i
                        protikladné informace.
                    </p>

                    <p class="ng-scope">
                        <strong>Tvrzení: </strong> "Donald Trump prohlásil, že byl podruhé zvolen prezidentem."<br>
                        <strong>Potvrzeno</strong> čím: "Donald Trump prohlásil, že u voleb obhájil svou prezidentskou
                        pozici."
                    </p>
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
                    <h5 class="modal-title" id="exampleModalLabel2">Nahlásit chybu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Tvrzení bude nahlášeno ke kontrole, zda splňuje pokyny z Ú<sub>1</sub> a nebude dočasně
                        přístupné dalším anotacím. Prosíme, přidejte poznámku, proč jste se pro nahlášení rozhodli.
                    </p>
                    <?= $form->field($model, 'flag')->hiddenInput(['value' => 0, 'id' => 'flag'])->label(false); ?>
                    <?= $form->field($model, 'flag_reason')->textarea()->label('Důvod k nahlášení')->hint('Např. "překlep", "nesmysl", "nejde o faktické tvrzení",...'); ?>
                </div>
                <div class="modal-footer">
                    <?= Html::submitButton('<i class="fas fa-flag"></i> Nahlásit chybu', ['class' => 'btn btn-warning autoflag',]) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>