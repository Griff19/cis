<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpDevice */

$this->title = $model->tmp_workplace_id;
$this->params['breadcrumbs'][] = ['label' => 'Tmp Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'tmp_workplace_id' => $model->tmp_workplace_id, 'devices_id' => $model->devices_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'tmp_workplace_id' => $model->tmp_workplace_id, 'devices_id' => $model->devices_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tmp_workplace_id',
            'devices_id',
        ],
    ]) ?>

</div>
