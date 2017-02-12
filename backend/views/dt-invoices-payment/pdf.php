<?php
/**
 * Ведомость на олату и согласование платежей
 *
 */

use backend\models\DtInvoicesPayment;
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider
 * @var $type string
 */

?>
<div class="dt-invoices-payment-index">
	<h3> Ведомость <?= $type ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
        'rowOptions' => function ($model) {
            return ['class' => $model->status != DtInvoicesPayment::PAY_OK ? : 'success'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			['attribute' => 'dt_invoices_id',
				'header' => 'Счет',
				'value' => function ($model) {
					return 'Счет №' . $model->dtInvoice->id . ' ' . $model->dtInvoice->doc_number;
				}
			],
			['attribute' => 'agreed_date', 'header' => 'Дата', 'format' => 'date'],
            ['attribute' => 'summ', 'header' => 'Сумма'],
            //['attribute' => 'employee.snp', 'header' => 'Согласовавший'],
            ['attribute' => 'status', 'header' => 'Статус', 'value' => 'statusString'],
			['class' => 'yii\grid\Column', 'header' => 'Подпись']
        ],
    ]); ?>
</div>
