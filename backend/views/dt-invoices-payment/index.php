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
            'agreed_date:date',
            'summ',
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status',
                'value' => function ($model) {
                    $status = $model->statusString;

                    if ($model->status == DtInvoicesPayment::PAY_AGREED) {
						$set = DtInvoicesPayment::PAY_REFER;
						$str = '(Отправить)';
						$title = 'Отправить бухгалтеру на оплату';
					}elseif ($model->status == DtInvoicesPayment::PAY_REFER) {
						$set = DtInvoicesPayment::PAY_OK;
						$str = '(Подтвердить)';
						$title = 'Подтвердить прошедшую оплату';
					}
					return $status . (empty($str) ? '' : ' '. Html::a($str, ['dt-invoices-payment/set-status', 'id' => $model->id, 'status' => $set, 'mode' => 1], ['title' => $title]));
                },
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
            ],
        ],
    ]); ?>
</div>
