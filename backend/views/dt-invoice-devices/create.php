<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoiceDevices */

$this->title = 'Добавление устройства в Счет';
$this->params['breadcrumbs'][] = ['label' => 'Счет id:'. $model->dt_invoices_id, 'url' => ['dt-invoices/view', 'id' => $model->dt_invoices_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-invoice-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
