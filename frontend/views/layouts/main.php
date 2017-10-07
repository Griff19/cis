<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/admin/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title> <?= Yii::$app->name ?> </title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('/admin/img/burenka.png', ['width' => '32px', 'style' => 'display: inline']) . ' Корпоративная Информационная Система',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];
    $menuItems[] = ['label' => 'Карта', 'url' => ['map']];
    if (Yii::$app->user->can('admin'))
        $menuItems[] = ['label' => 'Администрирование', 'url' => ['admin/site/admin']];
    elseif (Yii::$app->user->can('it'))
        $menuItems[] = ['label' => 'Администрирование', 'url' => ['admin/site/admin_workplace']];
    elseif (Yii::$app->user->can('auditor')) {
	    $menuItems[] = ['label' => 'Рабочие места', 'url' => ['admin/workplaces/index']];
	    $menuItems[] = ['label' => 'Список устройств', 'url' => ['admin/devices/index']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Авторизация', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <script>
            if (navigator.userAgent.indexOf('MSIE') >= 0 || navigator.userAgent.indexOf('.NET') >= 0) {
                document.writeln(
                    "<div class='alert alert-danger'>" +
                    "Внимание! При использовании данного браузера возможна не корректная работа системы.<br/>" +
                    "Для корректной работы рекомендуем использовать браузер " +
                    "<a href='https://www.google.ru/chrome/browser/desktop/index.html' target='_blank'>Google Chrome</a>" +
                    "</div>"
                );
            }
        </script>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Алтайская Буренка <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
