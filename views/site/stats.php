<?php

/* @var $this yii\web\View */

use app\models\Claim;
use app\models\Label;
use app\models\User;
use yii\bootstrap4\Html;

$this->title = 'Plnění';
?>
<div class="container">
    <h1><?= $this->title ?></h1>
    <ul>
        <?php foreach (User::find()->where(['>=','id',77])->all() as $user) {
            $quotas = [3, 7, 7, 35];
            for ($i = 0; $i < 3; $i++) {
                $quotas[$i] *= $user->getCoef();
            }
            if ($user->note == null) continue;
            echo Html::tag('li',
                Html::tag('span',
                    //'<strong class="fw-500">Ú<sub>0</sub></strong>: ' . Paragraph::find()->where(['candidate_of' => $user->id])->count() .
                    "<strong>{$user->username}</strong> ({$user->note}): " . 'Ú<sub>1</sub>a<strong class="fw-500">: ' . ($u1a = Claim::find()->where(['user' => $user->id])->andWhere(['IS', 'mutation_type', null])->count()) .
                    '</strong><sub>/' . $quotas[0] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>1</sub>b: <strong class="fw-500">' . ($u1b = Claim::find()->where(['user' => $user->id])->andWhere(['IS NOT', 'mutation_type', null])->count()) .
                    '</strong><sub>/' . $quotas[1] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>a: <strong class="fw-500">' . ($u2a = Label::find()->where(['user' => $user->id, 'oracle' => true])->count()) .
                    '</strong><sub>/' . $quotas[2] . '</sub>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>b</strong>: <strong class="fw-500">' . ($u2b = Label::find()->where(['user' => $user->id, 'oracle' => false])->count()) .
                    '</strong><sub>/' . $quotas[3] . '</sub> '
                    . ($u1a >= 3 && $u1b >= 7 && $u2a >= 7 && $u2b >= 35 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas text-danger fa-times"></i>'), ['class' => ' text-black nav-link ']),
                ['class' => 'text-left nav-item text-black']
            );
        } ?></ul>
    <h2>Dohromady (vč. anotací týmu AIC a zimní anotace)</h2>
    <p><?= Html::tag('span', 'Ú<sub>1</sub>a<strong class="fw-500">: ' . Claim::find()->where([])->andWhere(['IS', 'mutation_type', null])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>1</sub>b: <strong class="fw-500">' . Claim::find()->where([])->andWhere(['IS NOT', 'mutation_type', null])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>a: <strong class="fw-500">' . Label::find()->where(['oracle' => true])->count() .
            '</strong>&nbsp;&nbsp;&nbsp;Ú<sub>2</sub>b</strong>: <strong class="fw-500">' . Label::find()->where(['oracle' => false])->count() . '</strong>'
            , ['class' => ' text-black nav-link ']) ?></p>
</div>