<?php

/* @var $this yii\web\View */

use app\models\Claim;
use app\models\Label;
use app\models\User;
use yii\bootstrap4\Html;

$this->title = 'Plnění';
?>
<div class="container">
    <h1><?=$this->title ?></h1>
    <ul>
        <?php foreach (User::find()->all() as $user) {
            if ($user->note == null) continue;
            echo Html::tag('li',
                Html::tag('span',
                    //'<strong class="fw-500">Ú<sub>0</sub></strong>: ' . Paragraph::find()->where(['candidate_of' => $user->id])->count() .
                    "<strong>{$user->username}</strong> ({$user->note}): " . 'Ú<sub>1</sub>a<strong class="fw-500">: ' . Claim::find()->where(['user' => $user->id])->andWhere(['IS', 'mutation_type', null])->count() .
                    '</strong><sub>/5</sub>&nbsp;&nbsp;&nbsp;Ú<sub>1</sub>b: <strong class="fw-500">' . Claim::find()->where(['user' => $user->id])->andWhere(['IS NOT', 'mutation_type', null])->count() .
                    '</strong><sub>/15</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>a: <strong class="fw-500">' . Label::find()->where(['user' => $user->id, 'oracle' => true])->count() .
                    '</strong><sub>/5</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>b</strong>: <strong class="fw-500">' . Label::find()->where(['user' => $user->id, 'oracle' => false])->count() . '</strong><sub>/15</sub>'
                    , ['class' => ' text-black nav-link ']),
                ['class' => 'text-left nav-item text-black']
            );
        } ?></ul>
    <h2>Dohromady (vč. anotací týmu AIC)</h2>
    <p><?=Html::tag('span','Ú<sub>1</sub>a<strong class="fw-500">: ' . Claim::find()->where([])->andWhere(['IS', 'mutation_type', null])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>1</sub>b: <strong class="fw-500">' . Claim::find()->where([])->andWhere(['IS NOT', 'mutation_type', null])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>a: <strong class="fw-500">' . Label::find()->where(['oracle' => true])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>b</strong>: <strong class="fw-500">' . Label::find()->where(['oracle' => false])->count() . '</strong>'
            , ['class' => ' text-black nav-link '])?></p>
</div>