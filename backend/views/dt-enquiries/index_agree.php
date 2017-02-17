<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
/**
 * @var $this \yii\web\View
 * @var $model backend\models\DtEnquiries
 */
$this->registerAssetBundle('backend\assets\ModalAsset');

$this->title = 'Согласование';
$this->params['breadcrumbs'][] = ['label' => 'Заявка №' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
	'header' => '<h4 id = "modalHeader"></h4>',
	'id' => 'modal',
	'size' => 'modal-lg'
]);
echo '<div id = "modalContent"> Загрузка...</div>';
Modal::end();
?>

<h1> Согласование Заявки на оборудование </h1>
<p> Распечатайте сопроводительный листок:
	<?= Html::a('<span class="glyphicon glyphicon-print"></span> <b>pdf</b>',
		['dt-enquiries/pdf', 'id' => $model->id],
		['class' => 'btn btn-default', 'style' => 'padding: 3px 6px'])
	?>
</p>
<?= \yii\widgets\DetailView::widget([
	'model' => $model,
	'attributes' => [
		'id',
		['attribute' => 'employee_id',
			'value' => $model->employee ? $model->employee->snp : null
		],
		'create_date:date',
		'do_date:date',
		'create_time:datetime',
	]
])?>
<h3> Счета по заявке: </h3>
<p> Следующие документы необходимо отнести на подпись финансовому директору или лицу,
	исполняющему его обязанности: </p>
<?= \yii\grid\GridView::widget([
	/**
     * @var $provider \yii\data\ActiveDataProvider Отобранные данные по счетам
     * @see \backend\models\DtInvoicesSearch::search()
     */
    'dataProvider' => $provider,
	'columns' => [
		'id',
		'doc_number',
		'doc_date',
		'd_partners_id',
		//'d_partners_name',
		'delivery_type',
		'summ',
		['attribute' => 'summPay',
			'value' => function ($model) {
				$value = $model->summPay ? : 0;
				return $value . ' ' . Html::a('(Внести)', ['dt-invoices-payment/create', 'id' => $model->id],
					['title' => 'Внести оплату по данному счету']);
			},
			'format' => 'raw'
		],
		['attribute' => 'status',
			'value' => 'statusString'
		],
		['class' => '\yii\grid\Column',
			'content' => function ($model) {
				return Html::a('<span class = "glyphicon glyphicon-eye-open"></span>', '#', [
					'id' => 'linkModal1',
					//'data-target' => \yii\helpers\Url::to(['dt-invoices/view?id='.$model->id]),
					'data-target' => '/admin/dt-invoices/view?id='.$model->id. '&mode=1',
					'data-header' => 'Счет']) . ' ' .
					Html::a('<span class="glyphicon glyphicon-print"></span>', ['dt-invoices/create-pdf', 'id' => $model->id]);
			},
		]
	]
]) ?>
<p> <span class="glyphicon glyphicon-info-sign"></span>
	После согласования оплаты Вам необходимо ввести одобренную сумму в разделе "Платежей" соответствующего документа "Счет" </p>

