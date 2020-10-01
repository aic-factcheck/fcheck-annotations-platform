<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administrace';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            Vítejte v administraci!
            V současnosti umožňuje následující zásahy do obsahu stránek:
        </p>


        <?= Nav::widget([
            'options' => ['class' => ' flex-column'],
            'items' => [
                ['label' => 'Lokalizace', 'url' => ['/admin/i18n']],
                ['label' => 'Podstránky', 'url' => ['/page/index']],
                ['label' => 'Dílo', 'url' => ['/piece/index']],
                Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->email . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]) ?>
    </div>
</div>
