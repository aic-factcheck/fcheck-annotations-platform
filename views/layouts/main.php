<?php

/* @var $this View */

/* @var $content string */

use app\assets\AppAsset;
use app\models\Candidate;
use app\models\Claim;
use app\models\Label;
use app\models\Paragraph;
use app\widgets\Alert;
use app\widgets\Feedback;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-expand-lg navbar-light bg-light mb-3',
            ],
        ]);
        $quotas = [Yii::$app->params['quotas']['t1a'], Yii::$app->params['quotas']['t1b'], Yii::$app->params['quotas']['t2a'], Yii::$app->params['quotas']['t2b']];
        for ($i = 0; $i <= 3; $i++) {
            $quotas[$i] =  $quotas[$i] * (Yii::$app->user->isGuest?1:Yii::$app->user->identity->getCoef());
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => [
                /*Yii::$app->user->isGuest ? '' :
                    Html::tag('li',
                        Html::tag('span',
                            '<span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> 17.3. 12:00</span>'
                            , ['class' => ' text-black nav-link ']),
                        ['class' => 'text-center nav-item text-black']
                    ),*/
                Yii::$app->user->isGuest ? '' :
                    Html::tag('li',
                        Html::tag('span',
                            //'<strong class="fw-500">Ú<sub>0</sub></strong>: ' . Paragraph::find()->where(['candidate_of' => Yii::$app->user->id])->count() .
                            'Ú<sub>1</sub>a<strong class="fw-500">: ' . Claim::find()->where(['user' => Yii::$app->user->id])->andWhere(['IS', 'mutation_type', null])->count() .
                            '</strong><sub>/' . $quotas[0] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>1</sub>b: <strong class="fw-500">' . Claim::find()->where(['user' => Yii::$app->user->id])->andWhere(['IS NOT', 'mutation_type', null])->count() .
                            '</strong><sub>/' . $quotas[1] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>a: <strong class="fw-500">' . Label::find()->where(['user' => Yii::$app->user->id, 'oracle' => true])->count() .
                            '</strong><sub>/' . $quotas[2] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>b</strong>: <strong class="fw-500">' . Label::find()->where(['user' => Yii::$app->user->id, 'oracle' => false])->count() .
                            '</strong><sub>/' . $quotas[3] . '</sub>'
                            , ['class' => ' text-black nav-link ']),
                        ['class' => 'text-center nav-item text-black']
                    ),
                ['label' => 'Domů', 'url' => ['/site/index']],
                ['label' => 'Statistiky', 'url' => ['/site/statistics']],
                Yii::$app->user->isGuest ? (
                ['label' => 'Přihlásit', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Odhlásit (' . Yii::$app->user->identity->username .
                        ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    <?= Feedback::widget(['ajaxURL' => Url::to(['pinneapple/feedback']), 'highlightElement' => 0,]); ?>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; AICenter <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    <script type="text/javascript">
        <?php if (!Yii::$app->user->isGuest) {?>
        $(document).ready(function () {
            var start = new Date();
            var addend = 0;

            $(window).on("blur focus", function (e) {
                var prevType = $(this).data("prevType");
                if (prevType != e.type) {
                    switch (e.type) {
                        case "blur":
                            addend += new Date() - start;
                            break;
                        case "focus":
                            start = new Date();
                            break;
                    }
                }

                $(this).data("prevType", e.type);
            });

            $(window).on("beforeunload", function () {
                $.ajax({
                    url: '<?=Url::to(['site/timer'])?>',
                    type: 'GET',
                    data: {
                        time: new Date() - start + addend,
                        route: '<?=Yii::$app->controller->action->uniqueId?>'
                    }
                });
            });
        });
        <?php
        }
        ?>
        <?php if (Yii::$app->session->has('mutations')) {
            foreach (Yii::$app->session->get('mutations') as $mutation) {
                echo "$.ajax({url:'" . Url::to(['ctk/augment-knowledge', 'claim' => $mutation]) . "',method: 'GET'});";
            }
            Yii::$app->session->remove('mutations');
        } ?>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    </body>
    </html>
<?php $this->endPage() ?>