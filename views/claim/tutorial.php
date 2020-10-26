<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Tutoriál';

?>
<div class="container">
    <h1>Tutorial</h1>
</div>

<div class="container" ng-show="section1_show">
    <h2>Overview</h2>
    <p>The objective of this task is to generate both true and mutated claims from information extracted from Wikipedia.</p>
    <p>This task is separated into two screens that will be given back-to-back. The first screen (WF1a) is the claim generation part of the task. The second screen (WF1b) is the mutations to the generated claims.</p>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h3>WF1a</h3>
            <p>The objective of this task is to generate true claims</p>
            <p>The claims you generate will be based from a <strong>source sentence</strong> that was extracted from Wikipedia.</p>
            <img src="<?=Url::to(['images/wf1a.png'])?>" style="width:100%" />

        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h3>WF1b</h3>
            <p>The objective of this task is to generate modifications to the claims you have just created.</p>
            <p>There are six types of modification. For each claim you write, you will have to generate all six modifications.</p>
            <img src="<?=Url::to(['images/wf1b.png'])?>" style="width:100%" />
        </div>
    </div>


</div>


<div class="container ng-scope" ng-show="section2_show">
    <h2>WF1a Claim Generation</h2>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>What is a Claim?</h4>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <p>A claim is a single sentence expressing information (true or mutated) about a single aspect of one target entity.</p>
                <p>In WF1a, the claims you generate will be true claims based on a sentence given to you from Wikipedia</p>

                <p>Requirements/Conventions:</p>
                <ul>
                    <li>Claims must reference the target entity directly and avoid use of pronouns/nominals (e.g. he, she, it, the country).</li>
                    <li>Claims must not use speculative/cautious/vague language (e.g. may be, might be, it is reported that).</li>
                    <li>True claims should only be facts that can be deduced by information given in the source sentence and dictionary.</li>
                    <li>Minor variations over the entity name are acceptable: (e.g. Amazon River vs River Amazon), (JFK/John F Kennedy).</li>
                    <li>Correct capitalisation of entity names should be followed (India, not india).</li>
                    <li>Sentences should end with a period.</li>
                    <li>Numbers can be formatted in any appropriate English format (including as words for smaller quantities). </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>How complex should I make claims?</h4>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <p>The claims you generate in WF1a may vary in complexity depending on the information available.</p>
                <p>You could use just the source sentence to generate claims. But this will result in simple claims that are not challenging. </p>
                <p>We introduce a dictionary of terms containing additional knowledge that can be incorporated into your claims and make them more complex.</p>
            </div>
        </div>
    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>How many claims should I generate?</h4>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <p>The number of claims you generate will depend on the quality and information content of the sentence given to you that was extracted from Wikipedia.</p>
                <p>As a guide, you should aim to generate between 2-5 claims per sentence. But, if the sentence contains lots of information, this could be higher</p>
                <p>If the sentence is not informative, you can skip claim generation for this sentence.</p>
            </div>
        </div>
    </div>

    <h3>Example</h3>
    <hr>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>Generating Claims About</h4>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <h4><strong class="ng-binding">India</strong></h4>
            </div>
        </div>
    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>Source Sentence</h4>
                <p class="ng-binding">This is the sentence that is used to substantiate your claims about India</p>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <article class="sentence ng-binding">It shares land borders with Pakistan to the west; China, Nepal, and Bhutan to the northeast; and Myanmar (Burma) and Bangladesh to the east.</article>
            <article class="context ng-binding ng-hide" ng-show="showcontext">It is bounded by the Indian Ocean on the south, the Arabian Sea on the southwest, and the Bay of Bengal on the southeast. <strong class="ng-binding">It shares land borders with Pakistan to the west; China, Nepal, and Bhutan to the northeast; and Myanmar (Burma) and Bangladesh to the east.</strong> In the Indian Ocean, India is in the vicinity of Sri Lanka and the Maldives.</article>
            <a href="javascript:void(0)" ng-hide="showcontext" ng-click="toggleContext()">Show Context</a>
            <a href="javascript:void(0)" ng-show="showcontext" ng-click="toggleContext()" class="ng-hide">Hide Context</a>

        </div>

    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>Dictionary</h4>
                <p>Click the word for a definition. These definitions can be used to support the claims you write</p>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <!-- ngRepeat: (key, value) in dictionary --><div class="dictionary_item ng-scope" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item" class="ng-binding">Bangladesh</a></h4> <div ng-show="show_dictionary_item" class="ng-binding ng-hide">Bangladesh (; ;  , , lit. </div></div><!-- end ngRepeat: (key, value) in dictionary --><div class="dictionary_item ng-scope" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item" class="ng-binding">Bhutan</a></h4> <div ng-show="show_dictionary_item" class="ng-binding ng-hide">Bhutan  , officially the Kingdom of Bhutan  , is a landlocked country in Asia, and it is the smallest state located entirely within the Himalaya mountain range.</div></div><!-- end ngRepeat: (key, value) in dictionary --><div class="dictionary_item ng-scope" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item" class="ng-binding">China</a></h4> <div ng-show="show_dictionary_item" class="ng-binding ng-hide">China, officially the People's Republic of China (PRC), is a unitary sovereign state in East Asia and the world's most populous country, with a population of over 1.381 billion.</div></div><!-- end ngRepeat: (key, value) in dictionary --><div class="dictionary_item ng-scope" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item" class="ng-binding">Nepal</a></h4> <div ng-show="show_dictionary_item" class="ng-binding ng-hide">fi</div></div><!-- end ngRepeat: (key, value) in dictionary --><div class="dictionary_item ng-scope" ng-repeat="(key, value) in dictionary"><h4><a href="javascript:void(0)" ng-click="show_dictionary_item = ! show_dictionary_item" class="ng-binding">Pakistan</a></h4> <div ng-show="show_dictionary_item" class="ng-binding ng-hide">Pakistan ( or ; ), officially the Islamic Republic of Pakistan  , is a federal parliamentary republic in South Asia on the crossroads of Central and Western Asia.</div></div><!-- end ngRepeat: (key, value) in dictionary -->
            </div>
        </div>
    </div>

    <div class="row topmargin">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="callout left">
                <h4>True Claims (one per line)</h4>

                <a href="javascript:void(0)" ng-click="show_example = ! show_example">Example</a>
                <div ng-show="show_example" class="ng-hide">
                    <blockquote>The&nbsp;Amazon River, usually abbreviated to&nbsp;Amazon&nbsp;(US:&nbsp;/ˈæməzɒn/&nbsp;or&nbsp;UK:&nbsp;/ˈæməzən/;&nbsp;Spanish&nbsp;and&nbsp;Portuguese:&nbsp;Amazonas), in&nbsp;South America&nbsp;is the&nbsp;largest river&nbsp;by&nbsp;discharge&nbsp;volume of water in the world and according to some authors, the&nbsp;longest in length.</blockquote>
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
                        <li>The Amazon is might be the longest river <em>('might be' is cautious/vague language)</em></li>
                        <li>The Amazon River is home to river dolphins <em>(not explicitly mentioned in text).</em></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="ebox">
                <ul>
                    <li>One of the land borders that India shares is with the world's most populous country.</li>
                    <li>India borders 6 countries.</li>
                    <li>The Republic of India is situated between Pakistan and Burma.</li>
                </ul>
                <h4>Why did we chose these examples?</h4>
                <ul>
                    <li>The first claim uses information from the dictionary entry for China which states that it is the world's most populous country.</li>
                    <li>The second claim summarises the list of countries in the source sentence.</li>
                    <li>The third claim is deduced by Pakistan being West of India, and Burma being to the East.</li>
                </ul>
            </div>
        </div>
    </div>


    <div class="navigation_actions"><?=Html::a('Vyzkoušejte si to!',['claim/annotate','sandbox'=>1],['class'=>'btn btn-primary'])?></div>

</div>