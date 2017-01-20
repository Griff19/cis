<?php
/**
 * Представление pdf-версии документа "Счет" (dt-invoices/create-pdf)
 */

use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $model \backend\models\DtInvoices3
 * @var $dt_id_provider \yii\data\ActiveDataProvider
 * @var $dt_ip_provider \yii\data\ActiveDataProvider
 */

?>

<h3> Счет №<?= $model->doc_number ?> </h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
		'id',
		'doc_number',
		'doc_date:date',
		'partner.brand',
		'delivery_type',
		'summ',
		'summPay',
		'statusString'
    ]
])?>
<h4>Оплачиваемые устройства:</h4>
<?= GridView::widget([
	'dataProvider' => $dt_id_provider,
	'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
	'layout' => '{items}',
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],

		['attribute' => 'id', 'header' => 'ID'],
		['attribute' => 'dt_enquiries_id',
			'header' => 'Заявка',
			'value' => function ($model) {
				return $model->dt_enquiries_id
					? 'Заявка №' . $model->dt_enquiries_id .' от '. $model->dtEnquiry->create_date
					: '';
			},
		],
		['attribute' => 'type_id',
			'header' => 'Тип устройства',
			'value' => function ($model) {
				return DeviceType::getTitle($model->type_id);
			}
		],
		['attribute' => 'price', 'header' => 'Цена'],
		['attribute' => 'status', 'header' => 'Статус', 'value' => 'statusString'],
		['attribute' => 'note', 'header' => 'Заметка'],
	]
])?>

<h4>Уже произведенные оплаты:</h4>
<?= GridView::widget([
	'dataProvider' => $dt_ip_provider,
	'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
	'layout' => '{items}',
])?>
