<?php


namespace app\widgets;


class Feedback extends \dilden\feedbackwidget\DildenFeedback
{
    public $tpl = [
        'description' => '<div id="feedback-welcome"><div class="feedback-logo">Zpětná vazba</div><p>Toto okno Vám umožňuje poslat nám návrhy na úpravy anotační aplikace. Budeme rádi za hlášení problémů, nápady na vylepšení a obecné komentáře.</p><p>Začněte sepsáním stručné poznámky:</p><textarea id="feedback-note-tmp"></textarea><p>Next we&#39;ll let you identify areas of the page related to your description.</p><button id="feedback-welcome-next" class="feedback-next-btn feedback-btn-gray">Pokračovat</button><div id="feedback-welcome-error">Please enter a description.</div><div class="feedback-wizard-close"></div></div>',
        'highlighter' => '<div id="feedback-highlighter"><div class="feedback-logo">Zpětná vazba</div><p>Klikněte a táhněte myší k označení částí stránky, které nám pomohou pochopit Vaši zpětnou vazbu. Dialog lze posunout,pokud zavazí.</p><button class="feedback-sethighlight"><i class="fas fa-highlighter"></i> <span>Zvýraznit</span></button><label>Zvýraznit část obrazovky relevantní pro zpětnou vazbu.</label><button class="feedback-setblackout"><i class="fas fa-eraser"></i> <span>Začernit</span></button><label class="lower">Zakrýt jakékoli osobní údaje.</label><div class="feedback-buttons"><button id="feedback-highlighter-next" class="feedback-next-btn feedback-btn-gray">Pokračovat</button><button id="feedback-highlighter-back" class="feedback-back-btn feedback-btn-gray">Zpět</button></div><div class="feedback-wizard-close"></div></div>',
        'overview' => '<div id="feedback-overview"><div class="feedback-logo">Zpětná vazba</div><div id="feedback-overview-description"><div id="feedback-overview-description-text"><h3>Popis</h3></div></div><div id="feedback-overview-screenshot"><h3>Snímek obrazovky</h3></div><div class="feedback-buttons"><button id="feedback-submit" class="feedback-submit-btn feedback-btn-blue">Odeslat</button><button id="feedback-overview-back" class="feedback-back-btn feedback-btn-gray">Zpět</button></div><div id="feedback-overview-error">Please enter a description.</div><div class="feedback-wizard-close"></div></div>',
        'submitSuccess' => '<div id="feedback-submit-success"><div class="feedback-logo">Zpětná vazba</div><p>Thank you for your feedback. We value every piece of feedback we receive.</p><p>We cannot respond individually to every one, but we will use your comments as we strive to improve your experience.</p><button class="feedback-close-btn feedback-btn-blue">OK</button><div class="feedback-wizard-close"></div></div>',
        'submitError' => '<div id="feedback-submit-error"><div class="feedback-logo">Zpětná vazba</div><p>Sadly an error occured while sending your feedback. Please try again.</p><button class="feedback-close-btn feedback-btn-blue">OK</button><div class="feedback-wizard-close"></div></div>'
    ];

    public $initButtonText = "Poslat zpětnou vazbu";
}