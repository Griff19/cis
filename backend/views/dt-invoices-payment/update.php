<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoicesPayment */

$this->title = 'Update Dt Invoices Payment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Invoices Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dt-invoices-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
