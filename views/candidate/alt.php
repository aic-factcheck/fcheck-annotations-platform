<?php

/* @var $this yii\web\View */

/* @var $data array */

use app\helpers\Helper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Předvýběr kandidátních vět';
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
            <h4><?= Helper::detokenize($data['title']) ?>
                <span class="float-right">
                    <span class="badge badge-info"><?= Yii::$app->formatter->asDatetime($data['date']) ?></span>
                    <a href="<?= Url::current() ?>"
                       class="btn btn-warning btn-sm font-weight-bold">Přeskočit &raquo;</a>
                </span>
            </h4>
            <!--p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p-->
            <br/>
            <?php
            foreach ($data['blocks'] as $id => $block) {
                ?>
                <div class="card bg-white mb-2">
                    <div class="card-body text-block <?= $id == $data['id'] ? "bg-success" : '' ?>"><?php
                        $par_lines = explode(' .', $block);
                        $lines = [];
                        foreach ($par_lines as $line) {
                            if (strlen($line) > 1) {
                                $lines[] = $line . " .";
                            }
                        }
                        $i = 0;
                        foreach ($lines as $line) {
                            $sentence = [
                                "entity" => $id,
                                "sentence" => $i++,
                                "context" => $data["blocks"],
                                "sentences" => $lines,
                                "title" => $data['title'],
                                "date" => $data['date']
                            ];
                            echo Html::a(Helper::detokenize($line), ['alt', 'add' => json_encode($sentence)]);
                        }
                        $sents = explode(" .", $block);
                        ?></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
</div>
