<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */

$this->title = 'Редактировать: ' . $model->room_title;
$this->params['breadcrumbs'][] = ['label' => 'Отделы/Кабинеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->room_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="rooms-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
