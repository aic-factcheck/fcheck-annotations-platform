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
                <p>The objective of this task is to <strong>generate modifications to claims</strong>. The modifications
                    can be either <strong>true or false</strong>. You will be given specific instructions about the
                    types of modifications to make.</p>
                <ul>
                    <li>Use the <strong>original claims</strong> and the dictionary as the basis for your modifications
                        to facts about <strong class="ng-binding"></strong></li>
                    <li>Reference any entity directly (i.e. pronouns and nominals should not be used).</li>
                    <li>Minor variations of names are acceptable (e.g. John F Kennedy, JFK, President Kennedy).</li>
                    <li><strong>Avoid</strong> vague or cautions language (e.g. might be, may be, could be, is reported
                        that)
                    </li>
                    <li>Correct capitalisation of entity names should be followed (India, not india).</li>
                    <li>Sentences should end with a period.</li>
                    <li>Numbers can be formatted in any appropriate English format (including as words for smaller
                        quantities).
                    </li>
                    <!--<li>Additional world knowledge is given to the you in the form of a dictionary. This allows for more complex claims to be generated in a structured manner with information that can be backed up from Wikipedia</li>-->
                    <li>Some of the extracted text might not be accurate. These are still valid candidates for summary.
                        It is not your job to fact check the information
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
                            <p>Aim to spend about <strong>1 minute</strong> generating each claim. </p>
                            <p>You <strong>are allowed to incorporate your own world knowledge</strong> in making these
                                modifications and misinformation.</p>
                            <p>Generate both <strong>true and false</strong> modifications</p>
                            <p>All facts should reference any entity directly (i.e. pronouns and nominals should not be
                                used).</p>
                            <p>The mutations you produce <strong>should be <u>objective</u> (i.e. not subjective) and
                                    <u>verifiable</u> using information/knowledge that would be publicly
                                    available</strong></p>
                            <p>If it is not possible to generate facts or misinformation, leave the box blank.</p>
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