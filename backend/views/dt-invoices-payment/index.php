<?php
/**
 * Список согласованных платежей по счету
 * встраивается в представление dt-invoices\view
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

    <h3> История платежей: </h3>
    <p>
        <?= $modelDoc->status == DtInvoices::DOC_NEW ? Html::a('Добавить платеж', ['dt-invoices-payment/create', 'id' => $modelDoc->id ], ['class' => 'btn btn-success']) : ''; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            return ['class' => $model->status != DtInvoicesPayment::PAY_AGREED ? : 'success'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'dt_invoices_id',
            'agreed_date',
            'summ',
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status',
                'value' => function ($model) {
                    $str = $model->statusString;
                    if ($model->status == DtInvoicesPayment::PAY_AGREED) {
                        $str = $str . ' ' . Html::a('(Отправить)', '', ['title' => 'отправить бухгалтеру на оплату']);
                    }
                    return  $str;
                },
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
            ],
        ],
    ]); ?>
</div>
