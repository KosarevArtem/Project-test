<?php
use app\helpers\UIHelper;
use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\helpers\BaseStringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use function morphos\Russian\pluralize;


$this->title = $model->name;
?>
<div class="left-column">
    
        <div class="head-wrapper">
            <h3 class="head-main"><?= Html::encode($model->name); ?></h3>
            <p class="price price--big"><?= $model->budget; ?> ₽</p>
        </div>
        <p class="task-description">
            <?= Html::encode($model->description); ?></p>
        <a href="#" class="button button--blue">Откликнуться на задание</a>
        <div class="task-map">
            <img class="map" src="/img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1">
            <p class="map-address town"><?= $model->city->name; ?></p>
            <p class="map-address">Новый арбат, 23, к. 1</p>
        </div>
        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($reply as $replies): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?= $replies->user->userSettings->avatar_path; ?>" width="146" height="156" alt="<?= Html::encode($replies->task->performer->name); ?>">
            <div class="feedback-wrapper">
                <a href="<?= Url::to(['user/view', 'id' => $replies->user_id]); ?>" class="link link--block link--big"><?= $replies->task->performer->name; ?></a>
                <div class="response-wrapper">
                    <?=UIHelper::showStarRating($replies->user->rating); ?>
                    <?php $reviewsCount = $replies->user->getPerformerOpinions()->count(); ?>
                    <p class="reviews"><?=pluralize($reviewsCount, 'отзыв'); ?></p>
                </div>
                <p class="response-message">
                    <?= Html::encode($replies->description); ?>
                </p>

            </div>
            <div class="feedback-wrapper">
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($replies->dt_add); ?></span></p>
                <p class="price price--small"><?= $replies->price; ?>₽</p>
            </div>
            <div class="button-popup">
                <a href="#" class="button button--blue button--small">Принять</a>
                <a href="#" class="button button--orange button--small">Отказать</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $model->category->name; ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asRelativeTime($model->dt_add); ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDatetime($model->expire_dt); ?></dd>
                <dt>Статус</dt>
                <dd><?= $model->status->name; ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>