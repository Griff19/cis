<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoicesPayment */

$this->title = 'Изменить данные платежа: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Счет', 'url' => ['dt-invoices/view', 'id' => $model->dt_invoices_id]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="dt-invoices-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
