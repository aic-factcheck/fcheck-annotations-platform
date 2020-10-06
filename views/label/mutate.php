<?php

/* @var $this yii\web\View */
/* @var $sandbox bool */

/* @var $model \app\models\MutateForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Tvorba výroků';
?>
<div class="container">
<div ng-class="{sandbox:testingMode}" class="ng-scope sandbox">
    <div class="container wf1b">
        <h1>Claim Modification Task (WF1b)</h1>

        <?php if ($sandbox) { ?>
            <div>
                <h4 style="color:red; margin-bottom:0;">Sandbox Environment</h4>
                <div style="color:red;">Claims you write will be recorded. But will not form part of the final dataset.
                </div>
            </div>
        <?php } ?>


        <div class="row topmargin">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="callout">
                    <h4>Guidelines</h4>
                    <p>The objective of this task is to <strong>generate modifications to claims</strong>. The modifications can be either <strong>true or false</strong>. You will be given specific instructions about the types of modifications to make.</p>
                    <ul>
                        <li>Use the <strong>original claims</strong> and the dictionary as the basis for your modifications to facts about <strong class="ng-binding"></strong></li>
                        <li>Reference any entity directly (i.e. pronouns and nominals should not be used).</li>
                        <li>Minor variations of names are acceptable (e.g. John F Kennedy, JFK, President Kennedy).</li>
                        <li><strong>Avoid</strong> vague or cautions language (e.g. might be, may be, could be, is reported that)</li>
                        <li>Correct capitalisation of entity names should be followed (India, not india).</li>
                        <li>Sentences should end with a period.</li>
                        <li>Numbers can be formatted in any appropriate English format (including as words for smaller quantities). </li>
                        <!--<li>Additional world knowledge is given to the you in the form of a dictionary. This allows for more complex claims to be generated in a structured manner with information that can be backed up from Wikipedia</li>-->
                        <li>Some of the extracted text might not be accurate. These are still valid candidates for summary. It is not your job to fact check the information</li>
                    </ul>
                </div>
            </div>

        </div>


        <div class="row topmargin">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="callout left">
                    <h4>Modifying Claims About</h4>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="ebox">
                    <h4><strong class="ng-binding"><?= $model->claim->entity ?></strong></h4>
                </div>
            </div>
        </div>


        <div class="row topmargin">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="callout left">
                    <h4>Source Sentence</h4>
                    <p class="ng-binding">This is the sentence that is used to substantiate your claims about <?= $model->claim->entity ?></p>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <article class="sentence ng-binding"><?= $model->claim->sentence['sentence'] ?></article>
                <hr/>
                <article class="context ng-binding ng-hide" ng-show="showcontext"><?= $model->claim->sentence['context_before'] ?>
                    <strong><?= $model->claim->sentence['sentence'] ?></strong> <?= $model->claim->sentence['context_after'] ?></article>
                <a href="javascript:void(0)" ng-hide="showcontext" ng-click="showcontext = !showcontext">Show Context</a>
                <a href="javascript:void(0)" ng-show="showcontext" ng-click="showcontext = !showcontext" class="ng-hide">Hide
                    Context</a>

            </div>

        </div>


        <div class="row topmargin">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="callout left">
                    <h4>Dictionary</h4>
                    <p>Click the word for a definition. These definitions can be used to support the claims you write</p>
                    <p>The dictionary comes from the blue links on Wikipedia</p>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="ebox">
                    <?php foreach ($model->claim->sentence['dictionary'] as $key => $value) {?>
                        <div class="dictionary_item">
                            <h4><?=$key?></h4>
                            <div><?=$value?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>


        <div class="row topmargin">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="callout">
                    <h4>Modified Claims (one claim per line)</h4>

                    <p>Aim to spend about <strong>1 minute</strong> generating each claim. </p>

                    <p>You <strong>are allowed to incorporate your own world knowledge</strong> in making these modifications and misinformation.</p>

                    <p>Generate both <strong>true and false</strong> modifications</p>

                    <p>All facts should reference any entity directly (i.e. pronouns and nominals should not be used).</p>

                    <p>The mutations you produce <strong>should be <u>objective</u> (i.e. not subjective) and <u>verifiable</u> using information/knowledge that would be publicly available</strong> </p>

                    <p>If it is not possible to generate facts or misinformation, leave the box blank.</p>
                </div>
            </div>
        </div>
        <h2>Mutujeme výrok: <?=$model->claim->claim?></h2>
        <?php $form = ActiveForm::begin([
            'id' => 'mutate-form',
            'layout' => 'horizontal',
        ]); ?>
        <?php foreach (\app\models\Claim::MUTATIONS as $MUTATION){
            ?>
            <?=$form->field($model, "mutations[$MUTATION]")->textarea(['class' => 'w-100 form-control'])->label($MUTATION)?>
        <?php
        }?>
        <p class="text-right float-right">
            <?= Html::submitButton('Odeslat výroky', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Přeskočit', ['claim/annotate', 'sandbox' => $sandbox], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Home', ['site/index'], ['class' => 'btn btn-light']) ?>
        </p>
        <?php ActiveForm::end(); ?>
        <div class="navigation_actions">
            &nbsp;
        </div>
    </div>
</div></div>