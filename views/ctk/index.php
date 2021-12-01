<?php

/* @var $this yii\web\View */

/* @var $article Article */

/* @var $target int */

use app\models\Article;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Předvýběr kandidátních vět';
$baseUrl = Url::to(['ctk/nominate', 'paragraph' => '']);
$this->registerJs(<<<JS
$(".paragraph-selector").click(function() {
    var request = $.ajax({
      url: '$baseUrl'+$(this).data('id'),
      method: "GET"
    });
    location.reload();
});

$(document).ready(function() {
    $(document).keydown(function (e) {
      if ((e.ctrlKey||e.metaKey) && e.keyCode == 13) {
        $(".suggested").trigger('click');
      }
    });
});
JS
);
?>
<div class="container">
    <h1>Ú<sub>0</sub>: <?= $this->title ?></h1>
    <div class="alert alert-warning mt-0" role="alert">
        <h3 class="alert-heading">Pokyny</h3>
        <ul>
            <li>Cílem úkolu je během zhruba jedné minuty vybrat smysluplný blok textu pro extrakci atomických výroků
                v <?= Html::a('Ú<sub>1</sub>a', ['claim/annotate', 'sandbox' => 0]) ?></li>
            <li><strong>Kliknutím na konkrétní větu</strong> ve vzorku níže přidáte větu do množiny zdrojových bloků
                textu pro
                úkol Ú<sub>1</sub>a.
            </li>
            <li>Věta by měla obsahovat část jednoho, nebo více faktoidů - <strong>může se stát, že věta sama nebude pro
                    tvorbu tvrzení stačit</strong>. (Proto k větám automaticky před zařazením do systému
                přikládáme
                jejich kontext).
            </li>
            <li>Nebude-li žádný z kusů textu stačit pro tvorbu výroku, stiskněte <strong>Přeskočit &raquo;</strong></li>
            <li>Navrhovaný blok textu je <strong
                        style="BACKGROUND: rgba(40,167,69,.15)!important;COLOR: BLACK;FONT-WEIGHT: NORMAL;">zeleně
                    zvýrazněn</strong>, predikce se ale může mýlit
            </li>
        </ul>
    </div>

    <div class="card bg-light mb-3 zdrojovy-clanek">
        <div class="card-body">
            <h4><?= $article->get('title') ?>
                <span class="float-right">
                    <span class="badge badge-info"><?= Yii::$app->formatter->asDatetime($article->date) ?></span>
                    <a href="<?= Url::current() ?>"
                       class="btn btn-warning btn-sm font-weight-bold">Přeskočit &raquo;</a>
                </span>
            </h4>
            <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
            <br/>
            <?php
            foreach ($article->paragraphs as $paragraph) {
                ?>
                <div class="card bg-white mb-2 paragraph-selector <?= $paragraph->rank == $target ? "suggested" : '' ?>"
                     data-id="<?= $paragraph->id ?>">
                    <div class="card-body text-block <?= $paragraph->rank == $target ? "bg-success" : '' ?>">
                        <?= $paragraph->get('text') ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
</div>
<script src="https://raw.githubusercontent.com/jeresig/jquery.hotkeys/master/jquery.hotkeys.js"></script>
