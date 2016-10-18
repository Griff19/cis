<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Netints */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Сетевые интерфейсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="netints-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверенны что хотите удалить '.$model->ipaddr .' '. $model->mac .'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'mac',
            'vendor',
            'ipaddr',
            'domain_name',
            'type',
            'port_count',
            'device_id',
        ],
    ]) ?>

</div>
