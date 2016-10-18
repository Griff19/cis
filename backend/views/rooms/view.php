<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */

$this->title = $model->room_title;
$this->params['breadcrumbs'][] = ['label' => 'Отделы/Кабинеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверенны что хотите удалить'. $model->room_title .'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'room_title',
            'branch.branch_title',
        ],
    ]) ?>

</div>
