<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use app\models\UserSettings;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="page-header">
    <nav class="main-nav">
        <a href='#' class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (Yii::$app->controller->id !== 'auth'): ?>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <li class="list-item list-item--active">
                    <a class="link link--nav">Новое</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav">Мои задания</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav">Создать задание</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav">Настройки</a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
    <?php if (Yii::$app->controller->id !== 'auth'): ?>
    <?php $user = Yii::$app->user->identity; ?>
    <?php $userSettings = UserSettings::find()->where(['id' => 1])->one(); ?>
    <?php if ($userSettings->avatar_path): ?>
    <div class="user-block">
        <a href="#">
            <img class="user-photo" src="<?= $userSettings->avatar_path ?>" width="55" height="55" alt="Аватар">
        </a>
    <?php endif; ?>
        <div class="user-menu">
            <p class="user-name"><?= $user->name ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="#" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::toRoute(['auth/logout']); ?>" class="link">Выход из системы</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>

<main class="main-content container">
    <?=$content; ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
