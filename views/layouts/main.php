<?php

/* @var $this View */

/* @var $content string */

use app\assets\AppAsset;
use app\models\Candidate;
use app\models\Claim;
use app\models\Label;
use app\models\Paragraph;
use app\widgets\Alert;
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
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => [
                Yii::$app->user->isGuest ? '' :
                    Html::tag('li',
                        //'<strong class="fw-500">Ú<sub>0</sub></strong>: ' . Paragraph::find()->where(['candidate_of' => Yii::$app->user->id])->count() .
                        '<strong class="fw-500">Ú<sub>1</sub>a</strong>: ' . Claim::find()->where(['user' => Yii::$app->user->id])->andWhere(['IS', 'mutation_type', null])->count() .
                        ',&nbsp;&nbsp;&nbsp;<strong class="fw-500">Ú<sub>1</sub>b</strong>: ' . Claim::find()->where(['user' => Yii::$app->user->id])->andWhere(['IS NOT', 'mutation_type', null])->count() .
                        '<br/><strong class="fw-500">Ú<sub>2</sub>a</strong>: ' . Label::find()->where(['user' => Yii::$app->user->id, 'oracle' => true])->count() .
                        ',&nbsp;&nbsp;&nbsp;<strong class="fw-500">Ú<sub>2</sub>b</strong>: ' . Label::find()->where(['user' => Yii::$app->user->id, 'oracle' => false])->count(),
                        ['class' => 'mr-2 text-center']
                    ),
                ['label' => 'Domů', 'url' => ['/site/index']],
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

            $(window).on("beforeunload", function () {
                $.ajax({
                    url: '<?=Url::to(['site/timer'])?>',
                    type: 'GET',
                    data: {
                        time: new Date() - start,
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

    </body>
    </html>
<?php $this->endPage() ?>