<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Tutoriál';

?>
<div class="container">
    <h1>Tutorial</h1>
</div>

<div class="container">
    <h1>WF1b Feedback / Self-Assessment</h1>



    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>Modifying Claims About</h4>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <h4><strong>{{entity}}</strong></h4>
            </div>
        </div>
    </div>


    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>Source Sentence</h4>
                <p>This is the sentence that is used to substantiate your claims about {{entity}}</p>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <article class="sentence">{{sentence}}</article>
            <article class="context" ng-show="showcontext">{{context_before}} <strong>{{sentence}}</strong> {{context_after}}</article>
            <a href="javascript:void(0)" ng-hide="showcontext" ng-click="toggleContext()">Show Context</a>
            <a href="javascript:void(0)" ng-show="showcontext" ng-click="toggleContext()">Hide Context</a>

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
                <div class="dictionary_item" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item">{{key}}</a></h4> <div ng-show="show_dictionary_item">{{value}}</div></div>
            </div>
        </div>
    </div>



    <div class="row topmargin">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="callout">
                <h4>Modified Claims (one claim per line)</h4>

                <p>Aim to spend about 1 minute generating each claims </p>

                <p>You are allowed to incorporate your own world knowledge in making these modifications.</p>

                <p>If it is not possible to generate facts or misinformation, leave the box blank.</p>
            </div>
        </div>
    </div>


    <div ng-repeat="claim in claims" >
        <div class="row topmargin">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="callout left">
                    <h4>Original Claim</h4>
                    <div class="ebox">
                        <h4>{{claim}}</h4>
                    </div>
                </div>
            </div>

        </div>


        <div class="row topmargin">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#f6fca5  !important;">
                        <h4>Rephrase the original claim (Type 1)</h4>
                        <p>Modify the claim by rephrasing it or providing a paraphrase so that the meaning is preserved. You should aim to substitute entites and relations with synonyms if possible. </p>

                        <p>The mutated claim must be about {{entity}}. </p>



                        <p>You put:</p>
                        <textarea ng-model="rephrase[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_rephrase[claim]"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#FCD8CF !important; ">
                        <h4>Negate the original claim (Type 2)</h4>
                        <p>Change the sentence to negate the meaning.</p>

                        <p>The mutated claim must be about {{entity}}. </p>



                        <p>You put:</p>
                        <textarea ng-model="negate[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_negate[claim]"></textarea>
                    </div>

                </div>
            </div>
        </div>



        <div class="row topmargin">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#DFD5F1  !important;">
                        <h4>Substitution for a similar entity and/or relation (Type 3)</h4>
                        <p>Substitute either a relation, property and/or an attribute of {{entity}} in the claim to something else from the same set of things.</p>

                        <p>The mutated claim must be about {{entity}}. </p>

                        <p>You Put:</p>
                        <textarea ng-model="substitute_similar[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_similar[claim]"></textarea>
                    </div>
                </div>
            </div>


            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#A8CCC9  !important;">
                        <h4>Substitution for a dissimilar entity and/or relation  (Type 4)</h4>
                        <p>Substitute either a relation, property and/or an attribute of {{entity}} in the claim to something else from a differnet set of things.</p>

                        <p>The mutated claim must be about {{entity}}. </p>


                        <p>You put:</p>
                        <textarea ng-model="substitute_dissimilar[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_dissimilar[claim]"></textarea>
                    </div>
                </div>
            </div>
        </div>


        <div class="row topmargin">

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#fffae3  !important">
                        <h4>Make the Claim more Specific (So that the new claim implies the original) (Type 5)</h4>
                        <p>Modify the claim by replacing either a relation, property and/or an attribute of {{entity}} to something more specific that implies the original claim.</p>

                        <p>The mutated claim must be about {{entity}}. </p>

                        <p>You put:</p>
                        <textarea ng-model="specific[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_specific[claim]"></textarea>
                    </div>
                </div>
            </div>


            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="dictionary_item">
                    <div class="ebox" style="background-color:#beebe6  !important;">

                        <h4>Make the Claim more General (So that the new claim is implied by the original) (Type 6)</h4>
                        <p>Modify the claim by replacing either a relation, property and/or an attribute of {{entity}} to something more general that is implied by the original claim.</p>

                        <p>The mutated claim must be about {{entity}}. </p>

                        <p>You put:</p>
                        <textarea ng-model="general[claim]"></textarea>
                        <p>We came up with:</p>
                        <textarea readonly="readonly" ng-model="suggest_general[claim]"></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

            <form class="navigation_actions" ng-submit="submit()">
                <input type="submit" name="submit" value="Next Example" class="btn btn-primary"/>
            </form>
        </div>

    </div>

</div>