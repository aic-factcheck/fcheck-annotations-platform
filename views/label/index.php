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

                    <p class="ng-binding ng-scope">The purpose of this task is to identify evidence from a Wikipedia
                        page that can be used to support or refute simple factoid sentences called claims. The claims
                        are generated by humans (as part of the Ú<sub>1</sub> annotation workflow) from the Wikipedia
                        page about
                        Berlin Trilogy. Some claims are true. Some claims are fake. You must find the evidence from the
                        page that supports or refutes the claim.</p>

                    <p class="ng-scope">Other Wikipedia pages will also provide additional information that can serves
                        as evidence. For each line, we will provide extracts from the linked pages in the dictionary
                        column which appear when you "Expand" the sentence. The sentences from these linked pages that
                        contain relevant supplimentary information should be individually selected to record which
                        information is used in justifying your decisions.</p>


                    <h4 class="topmargin ng-scope">Step-by-step guide:</h4>

                    <ol class="gul ng-scope">
                        <li>Read and understand the claim</li>
                        <li>Read the Wikipedia page and identify sentences that contain relevant information.</li>
                        <li>On identifying a relevant sentence, press the <strong>Expand</strong> button to highlight
                            it. This will load the dictionary and the buttons to annotate it:
                            <ul>
                                <li>If the highlighted sentence contains enough information in a definitive statement to
                                    support or refute the claim, press the Supports or Refutes button to add your
                                    annotation. No information from the dictionary is needed in this case (this includes
                                    information from the main Wikipedia page). Then continue annotating from step 2.
                                </li>
                                <li>If the highlighted sentence contains some information supporting or refuting the
                                    claim but also needs supporting information, this can be added from the dictionary.
                                    <ol>
                                        <li>The hyperlinked sentences from the passage are automatically added to the
                                            dictionary
                                        </li>
                                        <li>If a sentence from the main Wikipedia article is needed to provide
                                            supporting information. Click “Add Main Wikipedia Page” to add it to the
                                            dictionary.<br>
                                            <strong>NB:</strong> if multiple sentences from the main Wikipedia page are
                                            selected, you don’t have to repeat the annotation for all the sentences as
                                            this will result in duplicates. Attempting to do so will result will result
                                            in this warning (that can be dismissed if new information will be
                                            added):<br>
                                            <code>This sentence has already been selected as part of another annotation
                                                that uses the original page. Unless you intend to add new information,
                                                continuing will result in a duplicate annotation.</code></li>
                                        <li>If the claim or sentence contains an entity that is not in the dictionary,
                                            then a custom page can be added by clicking “Add Custom Page”. Use a search
                                            engine of your choice to find the page and then paste the Wikipedia URL into
                                            the box.
                                        </li>

                                        <li>Tick the sentences from the dictionary <strong>that provide the minimal
                                                amount of supporting information needed to form your decision</strong>.
                                            If there are multiple equally relevant entries (such as a list of movies),
                                            then just select the first.
                                        </li>
                                    </ol>
                                </li>
                                <li>Once all required information is added, then press the Supports or Refutes button to
                                    add your annotation and continue from step 2.
                                </li>

                                <li>If the highlighted sentence and the dictionary do not contain enough information to
                                    support or refute the claim, press the Cancel button and continue from step 2 to
                                    identify more relevant sentences.
                                </li>

                            </ul>
                        </li>
                        <li>On reaching the end of the Wikipedia page. Press <strong>Submit</strong> if you could find
                            information that supports or refutes the claim. If you could not find any supporting
                            evidence, press <strong>Skip</strong> then select <strong>Not enough information</strong>
                        </li>
                    </ol>

                    <h4 class="topmargin ng-scope">What does it mean to Support or Refute</h4>
                    <p class="ng-scope">The objective is to find sentences that support or refute the claim.</p>
                    <p class="ng-scope">You must apply <strong>common-sense</strong> reasoning to the evidence you read
                        but <em>avoid applying your own world-knowledge</em> by basing your decisions on the information
                        presented in the Wikipedia page and dictionary.</p>

                    <p class="ng-scope">As a guide - you should ask yourself:</p>

                    <div class="ebox ng-scope"><em>If I was given only the selected sentences, do I have stronger reason
                            to believe claim is true (supported) or stronger reason to believe the claim is false
                            (refuted). If I'm not certain, what additional information (dictionary) do I have to add to
                            reach this conclusion.</em></div>


                    <p class="ng-scope">The following count as valid justifications for marking an item as
                        supported/refuted:</p>
                    <ul class="ng-scope">
                        <li>Sentence directly states information that supports/refutes the claim or states information
                            that is synonymous/antonymous with information in the claim<br>
                            <div class="ebox">
                                <strong>Claim:</strong> Water occurs artificially<br>
                                <strong>Refuted by:</strong> ``It also occurs in nature as snow, glaciers ...''
                            </div>
                            <div class="ebox">
                                <strong>Claim:</strong> Samuel L. Jackson was in the third movie in the Die Hard film
                                series.<br>
                                <strong>Supported by:</strong> ``He is a highly prolific actor, having appeared in over
                                100 films, including Die Hard 3.''
                            </div>
                        </li>

                        <li>Sentence refutes the claim through negation or quantification<br>
                            <div class="ebox">
                                <strong>Claim:</strong> Schindler's List received no awards.<br>
                                <strong>Refuted by:</strong> ``It was the recipient of seven Academy Awards (out of
                                twelve nominations), including Best Picture, Best Director...''
                            </div>
                        </li>

                        <li>Sentence provides information about a different entity and only one entity is permitted
                            (e.g. place of birth can only be one place)
                            <div class="ebox">
                                <strong>Claim:</strong> David Schwimmer finished acting in Friends in 2005.<br>
                                <strong>Refuted by:</strong> ``After the series finale of Friends in 2004, Schwimmer was
                                cast as the title character in the 2005 drama Duane Hopwood.''
                            </div>
                        </li>

                        <li>Sentence provides information that, in conjunction with other sentences from the dictionary,
                            fulfils one of the above criteria
                            <div class="ebox">
                                <strong>Claim:</strong> John McCain is a conservative.<br>
                                <strong>Refuted by:</strong> ``He was the Republican nominee for the 2008 U.S.
                                presidential election.'' <strong>AND</strong> ``The Republican Party's current ideology
                                is American conservatism, which contrasts with the Democrats' more progressive platform
                                (also called modern liberalism).''
                            </div>
                        </li>
                    </ul>

                    <h4 class="topmargin ng-scope">Adding Custom Pages</h4>
                    <p class="ng-scope">You may need to add a custom page from Wikipedia to the dictionary. This may
                        happen in cases where the claim discusses an entity that was not in the original Wikipedia
                        page</p>
                    <div class="ebox ng-scope">
                        <strong>Claim:</strong> Colin Firth is a Gemini.<br>
                        <strong>In Original Page:</strong> ``Colin Firth (born 10 September 1960)... ''<br>
                        <strong>Requires Additional Information from Gemini:</strong> ``Under the tropical zodiac, the
                        sun transits this sign between May 21 and June 21.''
                    </div>


                    <h4 class="topmargin ng-scope">Tense</h4>
                    <p class="ng-scope">The difference in verb tenses that do not affect the meaning should be
                        ignored.</p>

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


                    <h4 class="topmargin ng-scope">Skipping</h4>
                    <p class="ng-scope">There may be times where it is appropriate to skip the claim:</p>

                    <ul class="ng-scope">
                        <li>The claim cannot be verified using the information with the information provided:
                            <ul>
                                <li>If the claim could potentially be verified using other publicly available
                                    information. Select <strong>Not Enough Information</strong></li>
                                <li>If the claim can't be verified using any publicly available information (because
                                    it's ambiguous, vague, personal or implausible) select <strong>The claim is
                                        ambiguous or contains personal information</strong>
                                    <br> <strong>NB:</strong>Note that claim can be ambiguous even if the exact sentence
                                    can be found in Wikipedia (e.g. "The album achieved widespread popularity in
                                    America").
                                </li>
                            </ul>
                        </li>
                        <li>The claim contains typographical errors, spelling mistakes, is ungrammatical or could be
                            fixed with a very minor change<br>
                            Select <strong>The claim has a typo or grammatical error</strong>
                        </li>
                    </ul>

                    <h4 class="topmargin ng-scope">Flagging</h4>
                    <p class="ng-scope">You can submit the claim but flag it for further discussion. This could be
                        because:</p>
                    <ul class="ng-scope">
                        <li>The claim might contain potentially brand-damaging information (but remember that mutated
                            claims are never going to be presented to customers as facts)
                        </li>
                        <li>The claim fall between two slightly conflicting guidelines</li>
                    </ul>
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