<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoiceDevices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Invoice Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-invoice-devices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'dt_invoices_id',
            'devices_id',
            'price',
            'status',
            'note',
        ],
    ]) ?>

</div>
