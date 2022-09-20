<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавить задание';
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'validationUrl' => \yii\helpers\Url::to(['validate-form']),]); ?>
        <h3 class="head-main head-main">Публикация нового задания</h3>
        <?= $form->field($model, 'name')->label('Опишите суть работы'); ?>
        <?= $form->field($model, 'description')->textarea(); ?>
        <?= $form->field($model, 'category_id')->dropDownList(array_column($categories, 'name', 'id'))->label('Категория'); ?>
        <!--<div class="form-group">
            <label class="control-label" for="location">Локация</label>
            <input id="location" type="text">
        </div>-->
        <div class="half-wrapper">
            <?= $form->field($model, 'budget')->input('text', ['class' => 'budget-icon']); ?>
            <?= $form->field($model, 'expire_dt')->input('date'); ?>
        </div>
        <!--<p class="form-label">Файлы</p>
        <div class="new-file">
            <p class="add-file">Добавить новый файл</p>
        </div>-->
        <input type="submit" class="button button--blue" value="Опубликовать">
    <?php ActiveForm::end(); ?>
</div>