<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheetDevices */

$this->title = $model->dt_defsheets_id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Defsheet Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-defsheet-devices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'dt_defsheets_id' => $model->dt_defsheets_id, 'devices_id' => $model->devices_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'dt_defsheets_id' => $model->dt_defsheets_id, 'devices_id' => $model->devices_id], [
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
            'dt_defsheets_id',
            'devices_id',
            'reason:ntext',
            'status',
        ],
    ]) ?>

</div>
