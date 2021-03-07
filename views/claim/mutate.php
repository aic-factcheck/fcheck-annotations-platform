<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model MutateForm */

use app\helpers\Helper;
use app\models\Claim;
use app\models\MutateForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Tvorba tvrzení';
Helper::setEntities($ners = $model->claim->paragraph0->ners);
?>
<div class="container">
    <div class="ng-scope sandbox">
        <div class="container u1b">
            <h1>Mutace tvrzení (Ú<sub>1</sub>b)</h1>

            <?php if ($sandbox) { ?>
                <div>
                    <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
                    <div style="color:red;">Claims you write will be recorded. But will not form part of the final
                        dataset.
                    </div>
                </div>
            <?php } ?>

            <div class="alert  mt-3 alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Zlatá pravidla mutace tvrzení</h4>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    <li>Před prvním tvrzením si, prosím,
                        přečtěte <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info btn-sm', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
                    </li>
                    <li>
                        <i class="fas fa-exclamation"></i> Tvořte <strong>jen taková tvrzení</strong>, která <strong>má smysl fact-checkovat</strong>.
                    </li>
                    <li>
                        Není tedy třeba využít všech 6 způsobů obměny tvrzení, stačí zhruba <strong>3</strong>, klidně i pouze <strong>1</strong>.
                    </li>
                </ul>
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
                            <p>Cílem tohoto úkolu je <strong>vygenerovat mutace či obměny tvrzení</strong>. Mutace mohou být
                                <strong>pravdivé či nepravdivé</strong>. Podrobnější instrukce ohledně typů obměn jsou uvedeny
                                dále.</p>
                            <ul>
                                <li>Použijte <strong>původní tvrzení</strong> a poskytnuté související články jako základ pro
                                    jednotlivé
                                    obměny.<strong class="ng-binding"></strong></li>
                                <li>Na každou entitu se odkazujte přímo (tzn. zájmena by neměla být užívána).</li>
                                <li>Mírné variace jmen a názvů jsou přijatelné (např. John F Kennedy, JFK, prezident Kennedy).</li>
                                <li><strong>Vyvarujte se</strong> vágního, neurčitého a příliš opatrného jazyka (např. mohlo by,
                                    asi, snad, pravděpodobně atd.)
                                </li>
                                <li>Při vytváření obměn <strong>můžete zahrnout vlastní znalosti o světě</strong>.</li>
                                <li>Obměny, které vymyslíte, by měly být <strong>objektivní</strong> a <strong>ověřitelné</strong>
                                    pomocí veřejně dostupných informací a všeobecných znalostí.
                                </li>
                                <li>Dodržujte správné psaní velkých počátečních písmen u názvů (např. Indie a nikoliv indie).</li>
                                <li>Věty ukončujte tečkou.</li>
                                <li>Čísla mohou být uváděna v libovolném korektním formátu (pro menší čísla lze i slovy).
                                </li>
                                <li>Další informace jsou poskytnuty ve formě <em>znalostního rámce</em>, který by měl umožnit tvořit složitější tvrzení závislá na více článcích.
                                </li>
                                <!--<li>Additional world knowledge is given to the you in the form of a dictionary. This allows for more complex claims to be generated in a structured manner with information that can be backed up from Wikipedia</li>-->
                                <li>Některé z poskytnutých textů nemusejí být přesné či pravdivé. Přesto jsou to validní kandidáti -
                                    v této fázi není vašim úkolem ověřovat danou informaci.
                                </li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card bg-light mb-3 zdrojovy-clanek">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5"><h4 class="card-title">Zdrojový článek</h4>
                            <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
                        </div>
                        <div class="col-md-7">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <h5 class="card-title d-inline"><?= $model->claim->paragraph0->article0->get('title') ?> </h5>
                                    <?= Yii::$app->formatter->asDatetime($model->claim->paragraph0->article0->date) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-light mb-3 zdrojova-veta">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5"><h4 class="card-title">Zdrojový odstavec</h4>
                            <p class="card-text">Z tohoto odstavce a příslušného článku vycházejte při tvorbě tvrzení o
                                jedné z
                                jmenných entit.<br/>
                                <em>(<?= implode(", ", $ners) ?>)</em></p>
                        </div>
                        <div class="col-md-7">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <?php
                                    foreach ($model->claim->paragraph0->article0->paragraphs as $paragraph) {
                                        if ($paragraph->id == $model->claim->paragraph0->id) {
                                            echo Html::tag('p', Html::tag("strong", $paragraph->get('text')));
                                        } else {
                                            echo Html::tag("p", $paragraph->get('text'), ["class" => "context"]);
                                        }
                                    } ?>
                                    <?= Helper::expandLink('Zobrazit kontext', '.context', 'Skrýt kontext') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-light mb-3 slovnicek">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5"><h4 class="card-title">Znalostní rámec</h4>
                            <p class="card-text">Rozklikněte název článku pro zobrazení části, která byla vybrána jako
                                <strong>relevantní</strong> pro danou zdrojovou větu.</p>
                            <p class="card-text">Články ve <em>znalostním rámci</em> byly vybrány podle frekvence
                                výskytu
                                společných pojmenovaných entit, nebo pomocí sémantického vyhledávání vět z původního
                                článku.</p>
                        </div>
                        <div class="col-md-7">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <?php foreach ($model->claim->paragraph0->knowledge as $paragraph) {
                                        echo Helper::dictionaryItem($paragraph);
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card bg-primary text-white mb-3 zdrojovy-vyrok">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5"><h4 class="card-title">Původní tvrzení</h4></div>
                        <div class="col-md-7">
                            <div class="card bg-white text-black">
                                <div class="card-body">
                                    <h5 class="card-title d-inline"><?= $model->claim->claim ?> </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'mutate-form',
            ]); ?>
            <div class="card bg-light mb-3 zdrojovy-clanek">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="card-title">Obměny tvrzení</h4>
                            <ul>
                                <li>Cílem je strávit okolo <strong>1 minuty</strong> při generování každé obměny
                                    tvrzení.
                                </li>
                                <li>Pokud není možné obměnu vygenerovat, nechejte textové pole prázdné.</li>
                            </ul>
                        </div>
                        <?php foreach (Claim::MUTATION_COLORS as $mutation => $color) {
                            ?>
                            <div class="col-md-6 py-3">
                                <div class="card bg-<?= $color ?> text-white">
                                    <div class="card-body">
                                        <h5><?= Claim::MUTATION_NAMES[$mutation] ?> </h5>
                                        <p><?= Claim::MUTATION_DESCRIPTIONS[$mutation] ?> </p>
                                        <?= $form->field($model, "mutations[$mutation]")->textarea(['class' => 'w-100 form-control', 'rows' => 3, 'placeholder' => "Tvrzení vytvořené obměnou \"" . Claim::MUTATION_NAMES[$mutation] . "\""])->label(false) ?>
                                        <?= \yii\helpers\Html::tag("h6", Helper::expandLink("Zobrazit názorný příklad <i class=\"fas fa-eye-dropper\"></i>", ".$mutation-example", "Skrýt příklad <i class=\"fas fa-eye-dropper\"></i>"), ["class" => "text-right"]) ?>
                                        <div class="<?= $mutation ?>-example">
                                            <p><strong>Původní výrok:</strong>
                                                <?= Claim::MUTATION_EXAMPLES[$mutation][Claim::FROM] ?></p>
                                            <p><strong>Obměna:</strong>
                                                <?= Claim::MUTATION_EXAMPLES[$mutation][Claim::TO] ?></p>
                                            <p><em><strong>Vysvětlení</strong>
                                                <?= Claim::MUTATION_EXAMPLES[$mutation][Claim::BECAUSE] ?></em></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } ?>

                    </div>
                </div>
            </div>


            <p class="text-right">
                <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
                <?= Html::submitButton('<i class="fas fa-clipboard-check"></i> Odeslat tvrzení', ['class' => 'btn btn-primary']) ?>
            </p>
            <?php ActiveForm::end(); ?>
            <div class="navigation_actions">
                &nbsp;
            </div>
        </div>
    </div>
</div>