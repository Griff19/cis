<?php
/**
 * Представление pdf-версии документа "Счет" (dt-invoices/create-pdf)
 */

use backend\models\DeviceType;
use backend\models\DtInvoicesPayment;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var \backend\models\DtInvoices $model
 * @var \yii\data\ActiveDataProvider $dt_id_provider
 * @var \yii\data\ActiveDataProvider $dt_ip_provider
 */

?>

<h3> Счет №<?= $model->doc_number ?> от <?= $model->docDate ?></h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
		//'id',
		//'doc_number',
		//'doc_date:date',

		['attribute' => 'partner.name_partner',
			'value' => $model->partner->type_partner . ' ' . $model->partner->name_partner
		],
		'partner.inn',
		'partner.legal_address',
		'partner.bik',
		'partner.check_account',
		'partner.corr_account',
		'delivery_type',
		'summ',
		['attribute' => 'summPay', 'label' => 'Уже оплачено'],
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
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		['attribute' => 'agreed_date', 'header' => 'Дата согласования', 'format' => 'date'],
		['attribute' => 'summ', 'header' => 'Сумма'],
		['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
		['attribute' => 'status', 'header' => 'Статус', 'value' => 'statusString'],
	]
])?>
