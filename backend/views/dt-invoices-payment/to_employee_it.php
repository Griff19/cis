<?php
/**
 * Список платежей по счету
 * встраивается в представление site/it_index
 * Предоставляет возможность просматривать и манипулировать платежами по счету
 */

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
            return ['class' => $model->status != DtInvoicesPayment::PAY_OK ? : 'success'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			['attribute' => 'dt_invoices_id',
				'label' => 'Счет',
				'value' => function ($model) {
					return 'Счет №' . $model->dtInvoice->id . ' ' . $model->dtInvoice->doc_number;
				}
			],
            'agreed_date:date',
            'summ',
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status', 'value' => 'statusString'],
			['class' => 'yii\grid\Column',
				'header' => 'Действия',
				'content' => function ($model) {
					if ($model->status == DtInvoicesPayment::PAY_AGREED) {
						$set = DtInvoicesPayment::PAY_REFER;
						$str = 'Отправить';
						$title = 'Отправить бухгалтеру на оплату';
						$class = 'btn btn-primary';
					}elseif ($model->status == DtInvoicesPayment::PAY_REFER) {
						$set = DtInvoicesPayment::PAY_OK;
						$str = 'Подтвердить';
						$title = 'Подтвердить прошедшую оплату';
						$class = 'btn btn-success';
 					}
					return (empty($str) ? '' : Html::a($str,
						['dt-invoices-payment/set-status', 'id' => $model->id, 'status' => $set, 'mode' => 1],
						['class' => $class . ' btn-sm', 'title' => $title]));

				}
			]
        ],
    ]); ?>
</div>
