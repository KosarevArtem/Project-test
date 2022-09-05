<?php
use app\helpers\UIHelper;
use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\helpers\BaseStringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$this->title = $profile->name;
?>
<div class="left-column">
        <h3 class="head-main"><?= Html::encode($profile->name); ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="<?= $profile->userSettings->avatar_path; ?>" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <?=UIHelper::showStarRating($profile->rating, 'big'); ?>
                    <span class="current-rate"><?=$profile->rating; ?></span>
                </div>
            </div>
            <p class="user-description">
                <?= $profile->userSettings->about; ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?= $category->category->name; ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?= $profile->city->name; ?></span>, <span class="age-info">30</span> лет</p>
            </div>
        </div>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($opinions as $opinion): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?= $opinion->owner->userSettings->avatar_path; ?>" width="120" height="127" alt="Фото заказчика">
            <div class="feedback-wrapper">
                <p class="feedback"><?= $opinion->description; ?></p>
                <p class="task">Задание «<a href="#" class="link link--small"><?= $opinion->task->name ?></a>» <?= $opinion->task->status->name ?></p>
            </div>
            <div class="feedback-wrapper">
            <?=UIHelper::showStarRating($opinion->rate); ?>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($opinion->dt_add); ?></span></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                    <dt>Всего заказов</dt>
                    <dd><?= $completedTasks ?> выполнено, <?= $expiredTasks ?> провалено</dd>
                    <dt>Место в рейтинге</dt>
                    <dd>25 место</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= $profile->dt_add; ?></dd>
                    <dt>Статус</dt>
                    <dd>Открыт для новых заказов</dd>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--phone">+<?= $profile->userSettings->phone; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--email"><?= $profile->email; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--tg">@<?= $profile->userSettings->messenger; ?></a>
                </li>
            </ul>
        </div>
    </div>