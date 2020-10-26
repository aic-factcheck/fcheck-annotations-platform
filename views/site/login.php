<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';
?>
<div class="site-login container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Prosíme, zadejte svůj e-mail pro přihlášení:</p>
    <div class="row">
        <div class="col-lg-7">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group offset-sm-2">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
