<?php
/**
 * Список платежей по счету
 * встраивается в представление site/it_index
 * Предоставляет возможность просматривать и манипулировать платежами по счету
 */

use backend\models\DtInvoicesPayment;
use backend\models\DtInvoices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoicesPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-payment-index">

    <h3> Манипуляции с платежами:
		<?= Html::a('Ведомость на оплату', ['dt-invoices-payment/pdf', 'status' => DtInvoicesPayment::PAY_AGREED],
            ['class' => 'btn btn-default', 'data-method' => 'post'])?>
	</h3>
    <span class="alert-danger"><?= Yii::$app->session->getFlash('payment_error') ?></span>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            return ['class' => $model->status != DtInvoicesPayment::PAY_OK ? : 'success'];
        },
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			['attribute' => 'dt_invoices_id',
				'label' => 'Счет',
				'value' => function ($model) {
                    /** @var $model DtInvoicesPayment */
					return Html::a(
                        'Счет №' . $model->dtInvoice->id
                        . ' ' . $model->dtInvoice->doc_number
                        . ' (' . $model->dtInvoice->summ . 'р.)',
                        ['dt-invoices/view', 'id' => $model->dtInvoice->id]);
				},
                'format' => 'raw',
			],
            'agreed_date:date',
            ['attribute' => 'summ',
                'label' => 'Сумма / Оплачено',
                'value' => function ($model) {
                    /** @var $model DtInvoicesPayment */
                    return $model->summ . 'р. / '. $model->dtInvoice->summPay . 'р.';
                }
            ],
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status', 'value' => 'statusString'],
			['class' => 'yii\grid\Column',
				'header' => 'Действия',
				'content' => function ($model) {
                    $a = '';
                    /** @var $model DtInvoicesPayment */
					if ($model->status == DtInvoicesPayment::PAY_AGREED) {
						$a = Html::a('Отправить', ['dt-invoices-payment/set-status',
                            'id' => $model->id,
                            'status' => DtInvoicesPayment::PAY_REFER,
                            'mode' => 1],
                            ['class' => 'btn btn-primary btn-sm', 'title' => 'Отправить бухгалтеру на оплату']
                        );
					} elseif ($model->status == DtInvoicesPayment::PAY_REFER) {
						$a = Html::a('Подтвердить', ['dt-invoices-payment/set-status',
                            'id' => $model->id,
                            'status' => DtInvoicesPayment::PAY_OK,
                            'mode' => 1],
                            ['class' => 'btn btn-success btn-sm', 'title' => 'Подтвердить прошедшую оплату']
                        );
 					} elseif ($model->dtInvoice->summ <= $model->dtInvoice->summPay && $model->dtInvoice->status != DtInvoices::DOC_CLOSED) {
					    $a = Html::a('Закрыть', ['dt-invoices/save', 'id' => $model->dt_invoices_id], ['class' => 'btn btn-warning btn-sm']);
                    }
					return $a;
				}
			]
        ],
    ]); ?>

</div>
