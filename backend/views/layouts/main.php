<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use backend\models\Tasks;

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
        'brandLabel' => 'Алтайская Буренка',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']]
    ];
    if (Yii::$app->user->can('admin')) {
        $label = Html::tag('span', Tasks::CountNewMessage() ? : Tasks::CountMessage(),
            ['class' => Tasks::CountNewMessage() ? 'label label-danger' : 'label label-default']);
        $menuItems[] = ['label' => 'Сообщения ' . $label, 'url' => ['/tasks']];
        $menuItems[] = ['label' => 'Страница Админа', 'url' => ['/site/admin']];
        $menuItems[] = ['label' => 'Инвентаризация', 'url' => ['/inventory-acts']];

    } elseif (Yii::$app->user->can('it')) {
        $label = Html::tag('span', Tasks::CountNewMessage() ? : Tasks::CountMessage(),
            ['class' => Tasks::CountNewMessage() ? 'label label-danger' : 'label label-default']);
        $menuItems[] = ['label' => 'Сообщения ' . $label, 'url' => ['/tasks']];
        $menuItems[] = ['label' => 'Для сотруднков IT', 'url' => ['/site/employee-it']];
        //$menuItems[] = ['label' => 'Рабочие места', 'url' => ['/site/admin_workplace']];
        $menuItems[] = ['label' => 'Инвентаризация', 'url' => ['/workplaces']];
    }
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Логин', 'url' => ['/site/login']];
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
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; "Алтайская буренка" <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
