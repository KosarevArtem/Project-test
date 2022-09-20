<?php

/* @var $this yii\web\View */
/* @var $model LoginForm */

use app\models\LoginForm;
use yii\authclient\widgets\AuthChoice;
use yii\widgets\ActiveForm;
?>

<section class="modal enter-form form-modal" id="enter-form">
        <h2>Вход на сайт</h2>
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'validationUrl' => \yii\helpers\Url::to(['validate-form']),]); ?>
            <p>
                <?= $form->field($model, 'email')->input('email'); ?>
            </p>
            <p>
                <?= $form->field($model, 'password')->passwordInput()->label('Пароль'); ?>
            </p>
            <button class="button" type="submit">Войти</button>
            <?php ActiveForm::end(); ?>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>