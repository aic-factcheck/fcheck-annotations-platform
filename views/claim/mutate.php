<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model MutateForm */

use app\helpers\Helper;
use app\models\Claim;
use app\models\MutateForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Tvorba výroků';
Helper::setEntities($ners = $model->claim->paragraph0->ners);
?>
<div class="container">
    <div ng-class="{sandbox:testingMode}" class="ng-scope sandbox">
        <div class="container Ú<sub>1</sub>b">
            <h1>Mutace výroků (Ú<sub>1</sub>b)</h1>

            <?php if ($sandbox) { ?>
                <div>
                    <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
                    <div style="color:red;">Claims you write will be recorded. But will not form part of the final
                        dataset.
                    </div>
                </div>
            <?php } ?>

            <div class="alert alert-warning mt-0" role="alert">
                <h3 class="alert-heading">Pokyny</h3>
                <p>Cílem tohoto úkolu je <strong>vygenerovat mutace či modifikace výroků</strong>. Mutace mohou být <strong>pravdivé či nepravdivé</strong>. Podrobnější instrukce ohledně typů mutací budou uvedeny dále.</p>
                <ul>
                    <li>Použijte <strong>originální tvrzení</strong> a poskytnuté související články jako základ pro vaše mutace.<strong class="ng-binding"></strong></li>
                    <li>Na každou entitu se odkazujte přímo (tzn. zájména by neměla být užívána).</li>
                    <li>Mírné variace jmen a názvů jsou přijatelné (např. John F Kennedy, JFK, President Kennedy).</li>
                    <li><strong>Vyvarujte se</strong> vágního, neurčitého a příliš opatrného jazyka (např. mohlo by, asi, snad, pravděpodobně atd.)
                    </li>
                    <li>Dodržujte správné psaní velkých počátečních písmen u názvů (např. Indie a nikoliv indie).</li>
                    <li>Věty ukončujte tečkou.</li>
                    <li>Čísla mohou být uváděna v libovolném korektním formátu (pro menší čísla lze i slovy).
                    </li>
                    <li>Další informace jsou poskytnuty ve formě souvisejících článků, které by měly umožnit tvořit složitější a na vícero článcích závislé tvrzení.</li>
                    <!--<li>Additional world knowledge is given to the you in the form of a dictionary. This allows for more complex claims to be generated in a structured manner with information that can be backed up from Wikipedia</li>-->
                    <li>Některé z poskytnutých textů nemusejí být přesné či pravdivé. I přes to jsou to validní kandidáti - vašim úkolem není ověřovat danou informaci.
                    </li>
                </ul>
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
                        <div class="col-md-5"><h4 class="card-title">Obměňujete výrok</h4></div>
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
                            <p>Cílem je strávit maximálně okolo <strong>1 minuty</strong> při generování každé mutace výroku. </p>
                            <p>Při vytváření mutací <strong>můžete zahrnout vlastní znalosti o světě</strong>.</p>
                            <p>Vygenerujte <strong>pravdivé i nepravdivé</strong> mutace.</p>
                            <p>Všechna fakta by měla užívat a odkazovat se na jakoukoliv entitu přímo (tzn. zájména by neměla být používána).</p>
                            <p>Mutace, které vygenerujete, <strong>by měly být <u>objektivní</u> a
                                    <u>ověřitelné</u> pomocí veřejně dostupných informací a všeobecných znalostí.</strong></p>
                            <p>Pokud není možné vygenerovat mutaci, nechejte box prázdný.</p>
                        </div>
                        <?php foreach (Claim::MUTATION_COLORS as $mutation => $color) {
                            ?>
                            <div class="col-md-6 py-3">
                                <div class="card bg-<?= $color ?> text-white">
                                    <div class="card-body">
                                        <h5><?= $mutation ?> </h5>
                                        <?= $form->field($model, "mutations[$mutation]")->textarea(['class' => 'w-100 form-control', 'rows' => 3, 'placeholder' => "Výroky vytvořené obměnou $mutation"])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } ?>

                    </div>
                </div>
            </div>


            <p class="text-right">
                <?= Html::submitButton('Odeslat výroky', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Přeskočit', ['claim/annotate', 'sandbox' => $sandbox], ['class' => 'btn btn-warning']) ?>
                <?= Html::a('Home', ['site/index'], ['class' => 'btn btn-light']) ?>
            </p>
            <?php ActiveForm::end(); ?>
            <div class="navigation_actions">
                &nbsp;
            </div>
        </div>
    </div>
</div>