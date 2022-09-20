<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'validationUrl' => \yii\helpers\Url::to(['validate-form']),]); ?>
            <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <?= $form->field($model, 'name')->label('Ваше имя'); ?>
            <div class="half-wrapper">
                <?= $form->field($model, 'email')->input('email'); ?>
                <?= $form->field($model, 'city_id')->dropDownList(array_column($cities, 'name', 'id'), ['id' => 'town-user'])->label('Город'); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($model, 'password')->passwordInput()->label('Пароль'); ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($model, 'password_repeat')->passwordInput()->label('Повторите пароль'); ?>
            </div>
                <?= $form->field($model, 'is_performer', ['template' => '{input}{label}'])->checkbox(['value' => '1', 'checked' => true], false)->label( 'я собираюсь откликаться на заказы', ['class' => 'control-label checkbox-label']); ?>
                <?= Html::submitButton('Создать аккаунт', ['class' => 'button button--blue']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>