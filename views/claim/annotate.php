<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model ClaimForm */

use app\helpers\Helper;
use app\models\ClaimForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Tvorba tvrzení';
Helper::setEntities($ners = $model->paragraph->ners);
?>
<div class="container">
    <h1>Tvorba tvrzení (Ú<sub>1</sub>a)</h1>
    <?php if ($sandbox) { ?>
        <div>
            <h4 style="color:red; margin-bottom:0;">Zkušební verze</h4>
            <div style="color:red;">Vaše výroky budou uloženy, ale nebudou součástí finálního datasetu.</div>
        </div>
    <?php } ?>
    <div class="alert  mt-3 alert-warning alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">Zlatá pravidla extrakce tvrzení</h4>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            <li>Před prvním tvrzením si, prosím,
                přečtěte <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info btn-sm', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
            </li>
            <li>
                Tvořte <strong>jednoduchá pravdivá tvrzení</strong> vycházející ze <strong>zdrojového odstavce</strong>, která <strong>má smysl fact-checkovat</strong>.
            </li>
            <li>Pokud to zdrojový odstavec neumožňuje, nebo se Vám zdá nezajímavý, nebojte se ho  <?= Html::a('<i class="fas fa-forward"></i> Přeskočit', ['claim/annotate', 'sandbox' => $sandbox], ['class' => 'btn btn-default btn-sm']) ?>
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
                            <h5 class="card-title d-inline"><?= $model->paragraph->article0->get('title') ?> </h5>
                            <?= Yii::$app->formatter->asDatetime($model->paragraph->article0->date) ?>
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
                    <p class="card-text">Z tohoto odstavce a příslušného článku vycházejte při tvorbě tvrzení o jedné z
                        pojmenovaných entit.<br/>
                        <em>(<?= implode(", ", $ners) ?>)</em></p>
                </div>
                <div class="col-md-7">
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php
                            foreach ($model->paragraph->article0->paragraphs as $paragraph) {
                                if ($paragraph->id == $model->paragraph->id) {
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
                        <strong>relevantní</strong> pro daný zdrojový odstavec.</p>
                    <p class="card-text">Články ve <em>znalostním rámci</em> byly vybrány podle frekvence výskytu
                        společných pojmenovaných entit (jména osob, obcí, firem apod.), nebo pomocí sémantického vyhledávání odstavců z původního článku.</p>
                </div>
                <div class="col-md-7">
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php foreach ($model->paragraph->knowledge as $paragraph) {
                                echo Helper::dictionaryItem($paragraph);
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'claim-form',
        'action' => ['claim/annotate', 'sandbox' => $sandbox, 'paragraph' => $model->paragraph->id]
    ]); ?>

    <div class="card bg-light mb-3 tvrzeni">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5"><h4 class="card-title">Pravdivá tvrzení</h4>
                    <p>Snažte se strávit přibližně 2 minuty tvorbou <strong>1 až 5</strong> tvrzení z tohoto zdrojového odstavce.</p>
                    <p>Výsledná tvrzení oddělte koncem řádku (↵).</p>
                    <p>Pokud není zdrojový odstavec použitelný, stiskněte tlačítko <strong>Přeskočit</strong></p>
                    <?= Helper::expandLink("Příklad", "#example") ?>
                    <div id="example">
                        <blockquote>The&nbsp;Amazon River, usually abbreviated to&nbsp;Amazon&nbsp;(US:&nbsp;/ˈæməzɒn/&nbsp;or&nbsp;UK:&nbsp;/ˈæməzən/;&nbsp;Spanish&nbsp;and&nbsp;Portuguese:&nbsp;Amazonas),
                            in&nbsp;South America&nbsp;is the&nbsp;largest river&nbsp;by&nbsp;discharge&nbsp;volume of
                            water
                            in the world and according to some authors, the&nbsp;longest in length.
                        </blockquote>
                        <p><strong>Good</strong></p>
                        <ul>
                            <li>The Amazon River is located in South America.</li>
                            <li>The River Amazon is a river in the southern hemisphere.</li>
                            <li>Amazonas is another name for the Amazon River.</li>
                            <li>The Amazon is the longest river in the world.</li>
                            <li>The River Trent is shorter than the Amazon.</li>
                        </ul>
                        <p><strong>Bad</strong></p>
                        <ul>
                            <li>The Amazon is might be the longest river. <em>('might be' is cautious/vague
                                    language)</em>
                            </li>
                            <li>The Amazon River is home to river dolphins. <em>(not explicitly mentioned in text).</em>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7">
                    <?= $form->field($model, 'claims')->textarea(['placeholder' => 'Sem napište tvrzení, na každý řádek jedno.', 'rows' => 7, 'class' => 'w-100 form-control'])->label(false) ?>
                </div>
            </div>
        </div>
    </div>
    <p class="text-right">
        <?= Html::button('<i class="fas fa-info"></i> Pokyny', ['class' => 'btn btn-info', 'data' => ['toggle' => 'modal', 'target' => '#guidelines']]) ?>
        <?= Html::a('<i class="fas fa-forward"></i> Přeskočit', ['claim/annotate', 'sandbox' => $sandbox], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton('<i class="fas fa-clipboard-check"></i> Odeslat tvrzení', ['class' => 'btn btn-primary']) ?>
    </p>

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
                    <p>Cílem úkolu je <strong>vygenerovat pravdivá tvrzení</strong> ze zdrojového odstavce ČTK dat.</p>

                    <ul>
                        <li>Extrahujte jednoduchá (dále nedělitelná) tvrzení týkající se některých pojmenovaných entitit ze zdrojového textu.<br/><em>(<?= implode(", ", $ners) ?>)</em></li>
                        <li>Jako základ svého tvrzení použijte zdrojový odstavec a znalostní rámec.</li>
                        <li><strong>Pojmenované entity uvádějte přímo</strong> (vyhněte se používání zájmen apod.).</li>
                        <li>Drobné záměny jsou přípustné (např.
                            <em>Tomáš Garrigue Masaryk</em> , <em>TGM</em>, <em>Prezident Masaryk</em>).
                        </li>
                        <li><strong>Nepoužívejte vágní nebo opatrné formulace</strong> (např. <em>možná</em>, <em>mohl by</em>, <em>je
                                uváděno, že</em>, ...) <strong>s výjimkou řádového zaokrouhlení čísel</strong> (<em>desítky, stovky,...</em>)
                        </li>
                        <li>Dodržujte základní pravidla psaní velkých písmen (pište <em>Indie</em> místo <em>indie</em>).</li>
                        <li>Věty končete tečkou.</li>
                        <li>Čísla mohou být zapisována jakýmkoli vhodným českým způsobem (včetně slovního zápisu pro nízké
                            hodnoty).
                        </li>
                        <li>Některé předložené informace mohou být nepřesné, přesto s jejich správností počítejte.
                            Není vašim úkolem je ověřovat.
                        </li>

                    </ul>

                    <h4 class="alert-heading">Vlastní znalosti</h4>

                    <ul>
                        <li><strong>Nezapojujte</strong> své vlastní znalosti nebo domněnky o světě.</li>
                        <li>Doplňující informace vám jsou předány pomocí znalostního rámce. Ten obsahuje informace nad rámec původního odstavce, které mohou pomoct s vytvořením složitějších tvrzení. (Omezujeme vás pouze na znalostní rámec, abychom byli schopni všechna tvrzení z Ú<sub>1</sub> navázat na konkrétní zdroje z ČTK dat.)</li>
                        <li>Pokud není zdrojový odstavec textu použitelný, přeskočte ho.</li>
                        <li>Pokud není znalost ve znalostním rámci relevantní nebo vhodná, ignorujte ji.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="navigation_actions">
        &nbsp;
    </div>


    <!--<div style="position:fixed; top:0px; right:30px; border: 1px solid #333; background:white">
        Time on this page: <time>{{timer}}</time><br/>
        Number of claims: {{count}} this session.
    </div>-->

</div>