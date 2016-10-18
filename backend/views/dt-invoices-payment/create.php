<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model->dtInvoice \backend\models\DtInvoices */
/* @var $model backend\models\DtInvoicesPayment */

$this->title = 'Ввести оплату по Счету №' . $model->dtInvoice->doc_number;
$this->params['breadcrumbs'][] = ['label' => 'Счет №' . $model->dtInvoice->doc_number, 'url' => ['dt-invoices/view', 'id' => $model->dt_invoices_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-invoices-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
