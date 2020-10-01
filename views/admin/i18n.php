<?php

/* @var $this yii\web\View */

/* @var $model app\models\ContactForm */

use kartik\editors\Codemirror;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = 'Lokalizace';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="site-contact container">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Thank you for contacting us. We will respond to you as soon as possible.
        </div>

        <p>
            Note that if you turn on the Yii debugger, you should be able
            to view the mail message on the mail panel of the debugger.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                    Please configure the
                <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            Kvůli bezpečnosti a prevenci vzniku chyb na stránce je potřeba aby lokalizaci schválil správce stránky <strong><?=Html::a(Yii::$app->params['adminEmail'],"mailto:".Yii::$app->params['adminEmail'])?></strong>.
        </p>
        <p>
            Po odeslání  upraveného lokalizačního souboru níže jej zkontroluje a obratem nasadí.
        </p>

        <div class="row">
            <div class="col-lg-12">

                <?php $form = ActiveForm::begin(['id' => 'contact-form',
                    'layout' => 'horizontal',]); ?>

                <?= $form->field($model, 'name')->textInput() ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->widget(Codemirror::class,['preset' => Codemirror::PRESET_PHP,])->label("Lokalizační soubor")->hint("načtený ze současné lokalizace stránek") ?>
                <br/>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</section>
