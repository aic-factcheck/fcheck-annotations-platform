<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model ClaimForm */

use app\models\ClaimForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Tvorba výroků';
?>
<div class="container">
    <h1>Claim Generation Task (WF1a)</h1>
    <?php if ($sandbox) { ?>
        <div>
            <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
            <div style="color:red;">Claims you write will be recorded. But will not form part of the final dataset.
            </div>
        </div>
    <?php } ?>
    <div class="row topmargin">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Guidelines</h4>
                    <p>The objective of this task is to <strong>generate true claims</strong> from this source sentence
                        that
                        was extracted from Wikipedia. </p>

                    <ul>
                        <li><strong class="ng-binding">Extract simple factoid claims about Český kraulař Svoboda skončil
                                na
                                ME čtvrtý, ale na medaili neměl</strong> given the source sentence
                        </li>
                        <li>Use the <strong>source sentence and dictionary</strong> as the basis for your claims.</li>
                        <li><strong>Reference any entity directly</strong> (pronouns and nominals should not be used).
                        </li>
                        <li>Minor variations of names are acceptable (e.g. John F Kennedy, JFK, President Kennedy).</li>
                        <li><strong>Avoid vague or cautions language</strong> (e.g. might be, may be, could be, is
                            reported
                            that)
                        </li>
                        <li>Correct capitalisation of entity names should be followed (India rather than india).</li>
                        <li>Sentences should end with a period.</li>
                        <li>Numbers can be formatted in any appropriate English format (including as words for smaller
                            quantities).
                        </li>
                        <li>Some of the extracted text might not be accurate. These are still valid candidates for
                            summary.
                            It is not your job to fact check the information
                        </li>

                    </ul>

                    <h4>World Knowledge</h4>

                    <ul>
                        <li><strong>Do not</strong> incorporate your own knowledge or beliefs.</li>
                        <li>Additional Knowledge is given to you in the dictionary. This dictionary contains additional
                            information that may be helpful in making more complex claims. <br>(we prefer you to use the
                            dictionary because this information can be backed up from Wikipedia)
                        </li>
                        <li>If the source sentence is not suitable, leave the box blank to skip.</li>
                        <li>If a dictionary entry is not suitable or uninformative, ignore it.</li>
                    </ul>

                </div>
            </div>
        </div>

    </div>


    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-body left">
                    <h4>Generating Claims About</h4>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <h4><strong class="ng-binding"><?= $model->sentence['entity'] ?></strong></h4>
            </div>
        </div>
    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-body left">
                    <h4>Source Sentence</h4>
                    <p class="ng-binding">This is the sentence that is used to substantiate your claims
                        about <?= $model->sentence['entity'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <article class="sentence ng-binding"><?= $model->sentence['sentence'] ?></article>
            <hr/>
            <article class="context ng-binding ng-hide" ng-show="showcontext"><?= $model->sentence['context_before'] ?>
                <strong><?= $model->sentence['sentence'] ?></strong> <?= $model->sentence['context_after'] ?></article>
            <a href="javascript:void(0)" ng-hide="showcontext" ng-click="showcontext = !showcontext">Show Context</a>
            <a href="javascript:void(0)" ng-show="showcontext" ng-click="showcontext = !showcontext" class="ng-hide">Hide
                Context</a>

        </div>

    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-body left">
                    <h4>Dictionary</h4>
                    <p>Click the word for a definition. These definitions can be used to support the claims you write or
                        make the claims more complex by making a deduction using the dictionary definitions</p>
                    <p>The dictionary comes from the blue links on Wikipedia. This may be empty if the passage from
                        Wikipedia contains no links.</p>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <?php foreach ($model->sentence['dictionary'] as $key => $value) { ?>
                    <div class="dictionary_item">
                        <h4><?= $key ?></h4>
                        <div><?= $value ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'claim-form',
        'layout' => 'inline',
    ]); ?>


    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-body left">
                    <h4>True Claims (one per line)</h4>
                    <p>Aim to spend about 2 minutes generating <strong>2-5</strong> claims from this source sentence</p>
                    <p>If the source sentence is uninformative, press the skip button</p>

                    <a href="javascript:void(0)" ng-click="show_example = ! show_example">Example</a>
                    <div ng-show="show_example" class="ng-hide">
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
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?= $form->field($model, 'claims')->textarea(['autofocus' => true, 'class' => 'w-100 form-control']) ?>
            <?= $form->field($model, 'sentence_json')->hiddenInput(['value' => json_encode($model->sentence)]) ?>
        </div>
    </div>
    <p class="text-right float-right">
        <?= Html::submitButton('Odeslat výroky', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Přeskočit', ['claim/annotate', 'sandbox' => $sandbox], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Home', ['site/index'], ['class' => 'btn btn-light']) ?>
    </p>
    <?php ActiveForm::end(); ?>
    <div class="navigation_actions">
        &nbsp;
    </div>


    <!--<div style="position:fixed; top:0px; right:30px; border: 1px solid #333; background:white">
        Time on this page: <time>{{timer}}</time><br/>
        Number of claims: {{count}} this session.
    </div>-->

</div>