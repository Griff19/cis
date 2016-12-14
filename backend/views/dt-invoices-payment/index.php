<?php
/**
 * Список согласованных платежей по счету
 * встраивается в представление dt-invoices\view
 */
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
        <?= $modelDoc->status == DtInvoices::DOC_NEW ? Html::a('Согласовать', ['dt-invoices-payment/create', 'id' => $modelDoc->id ], ['class' => 'btn btn-success']) : ''; ?>
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
            ['attribute' => 'status',
                'value' => 'statusString'
            ],
            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
            ],
        ],
    ]); ?>
</div>
