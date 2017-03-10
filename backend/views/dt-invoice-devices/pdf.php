<?php
/**
 * Ведомость на согласование платежей
 * @see \backend\controllers\DtInvoiceDevicesController::actionPdf()
 */

use backend\models\DeviceType;
use yii\grid\GridView;

/**
 *
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $type string
 */

?>
<div class="dt-invoices-payment-index">
	<h3> Ведомость <?= $type ?></h3>
    <p>Начальник отдела информатизации:________________________________________________________</p>
    <p>Финансовый директор:____________________________________________________________________</p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			['attribute' => 'dt_invoices_id',
				'header' => 'Счет',
				'value' => function ($model) {
					return 'Счет №' . $model->dtInvoice->id . ' ' . $model->dtInvoice->doc_number;
				}
			],
			['attribute' => 'type_id', 'header' => 'Тип',
                'value' => function ($model) {
                return DeviceType::getTitle($model->type_id);
            }],
            ['attribute' => 'price', 'header' => 'Сумма'],
            ['attribute' => 'status', 'header' => 'Статус', 'value' => 'statusString'],
			['class' => 'yii\grid\Column', 'header' => 'Подпись']
        ],
    ]); ?>
</div>
