<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VoipNumbers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Внутренние номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voip-numbers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверенны что хотите удалить номер'. $model->voip_number .'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'voip_number',
            'secret',
            'description',
            'context',
            'device_id',
        ],
    ]) ?>

</div>
