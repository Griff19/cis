<?php
/**
 * Список платежей по счету
 * встраивается в представление site/it_index
 * Предоставляет возможность просматривать и манипулировать платежами по счету
 */
use backend\models\DtInvoices;
use backend\models\DtInvoicesPayment;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoicesPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-payment-index">

    <h3> Манипуляции с платежами: </h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            return ['class' => $model->status != DtInvoicesPayment::PAY_AGREED ? : 'success'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'agreed_date:date',
            'summ',
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status',
                'value' => 'statusString'
            ],
            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
            ],
        ],
    ]); ?>
</div>
