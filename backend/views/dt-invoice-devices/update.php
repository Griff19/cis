<?php

use yii\helpers\Html;
use backend\models\DeviceType;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoiceDevices */

$this->title = 'Редактировать строку ' . DeviceType::getTitle($model->type_id);
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['dt-invoices/index']];
$this->params['breadcrumbs'][] = ['label' => 'Счет id:'. $model->dt_invoices_id, 'url' => ['dt-invoices/view', 'id' => $model->dt_invoices_id]];
$this->params['breadcrumbs'][] = DeviceType::getTitle($model->type_id);
?>
<div class="dt-invoice-devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
