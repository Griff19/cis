<?php

use backend\models\DtInvoices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoicesPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-payment-index">

    <h3> История платежей: </h3>
    <p>
        <?= $modelDoc->status == DtInvoices::DOC_NEW ? Html::a('Внести оплату', ['dt-invoices-payment/create', 'id' => $modelDoc->id ], ['class' => 'btn btn-success']) : ''; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'dt_invoices_id',
            'agreed_date',
            'summ',
            'employee.snp',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
