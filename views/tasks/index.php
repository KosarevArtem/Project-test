<?php
/**
 * @var Task[] $models
 * @var Task $task
 * @var View $this
 * @var Category[] $categories
 * @var Pagination $pages
 */

use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\helpers\BaseStringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;


$this->title = 'Задания';
?>
<div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <div class="task-card">
            <?php foreach ($models as $model): ?>
            <div class="header-task">
                <a  href="<?= Url::to(['tasks/view', 'id' => $model->id]); ?>" class="link link--block link--big"><?= Html::encode($model->name); ?></a>
                <p class="price price--task"><?= $model->budget; ?> ₽</p>
            </div>
            <p class="info-text"><?= Yii::$app->formatter->asRelativeTime($model->dt_add); ?></p>
            <p class="task-text"><?= Html::encode(BaseStringHelper::truncate($model->description, 200)); ?>
            </p>
            <div class="footer-task">
                <?php if ($model->city_id): ?>
                <p class="info-text town-text"><?= $model->city->name; ?> </p>
                <?php endif; ?>
                <p class="info-text category-text"><?= $model->category->name; ?></p>
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="pagination-wrapper">
        <?=LinkPager::widget([
            'pagination' => $pages,
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'linkOptions' => ['class' => 'link link--page'],
            'nextPageLabel' => '',
            'prevPageLabel' => '',
            'maxButtonCount' => 5
        ]); ?>
    </div>
    </div>
    <div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin(); ?>
                <h4 class="head-card">Категории</h4>
                <div class="checkbox-wrapper">
                <?= $form->field($task, 'category_id')->checkboxList(ArrayHelper::map($categories, 'id', 'name'), [
            'tag' => false,
            'item' => function ($index, $label, $name, $checked, $value) {
                $checked = $checked ? 'checked' : '';
                return 
                "<div> <input type='checkbox' id='$index' name='$name' 'value'='$value' $checked> <label class='control-label' for='$index'> $label </label> </div>";
            }
        ])->label(false) ?>
                <h4 class="head-card">Дополнительно</h4>
            <div class="checkbox-wrapper">
                    <?=$form->field($task, 'noResponses', ['template' => '{input}{label}'])->checkbox(['class' => 'checkbox'], false)->label( 'Без отклика', ['class' => 'control-label']); ?>
            </div>
            <div class="checkbox-wrapper">
                <?=$form->field($task, 'noLocation', ['template' => '{input}{label}'])->checkbox(['class' => 'checkbox'], false)->label( 'Удаленная работа', ['class' => 'control-label']); ?></div>
                <h4 class="head-card">Период</h4>
                    <?=$form->field($task, 'filterPeriod', ['template' => '{input}'])->dropDownList([
                        '3600' => 'За последний час', '86400' => 'За сутки', '604800' => 'За неделю'
                    ], ['prompt' => 'Выбрать']); ?>
                <input type="submit" class="button button--blue" value="Искать">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>