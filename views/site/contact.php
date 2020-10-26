<?php

/* @var $this yii\web\View */

/* @var $model app\models\ContactForm */

use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = Yii::t('app','Kontakt');
$this->params['breadcrumbs'][] = $this->title;
?>

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
        <?=Yii::t('app','Kontaktujte mě, prosím, vyplněním formuláře níže. Děkuji.')?>
    </p>

    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['id' => 'contact-form',
                'layout' => 'horizontal',]); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'subject') ?>

            <?= $form->field($model, 'body')->textarea() ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','Odeslat'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

<?php endif; ?>
