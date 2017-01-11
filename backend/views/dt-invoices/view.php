<?php
/**
 * Представление документа "Счет"
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use backend\models\Images;
use backend\models\DtInvoices;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoices */

$this->registerAssetBundle('backend\assets\ModalAsset');

$this->title = 'Документ №' . $model->doc_number;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
	'header' => '<h4 id = "modalHeader"></h4>',
	'id' => 'modal',
	'size' => 'modal-lg'
]);
echo '<div id="modalContent"></div>';
Modal::end();
Modal::begin([
	'id' => 'modalImg',
	'size' => 'modal-lg'
]);
$key = md5('dt-invoices' . $model->id);
echo Html::img('/admin/' . Images::getLinkfile($key), ['style' => 'width: 100%', 'alt' => 'Отсутствует изображение']);
Modal::end();
?>
<div class="dt-invoices-view">

	<div class="row">
		<div class="col-lg-6">
			<h1> <?= Html::encode($this->title) ?></h1>
			<p>
				<?php
				if ($model->status == DtInvoices::DOC_NEW) {
					echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
					echo Html::a('Удалить', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Уверенны что хотите удалить документ?',
							'method' => 'post',
						],
					]);
				}?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'doc_number',
					'doc_date',
					'partner.brand',
					'delivery_type',
					'summ',
					'summPay',
					'statusString'
				],
			]) ?>
		</div>
		<div class="col-lg-6">
			<div class="img-thumbnail img-block" style="margin-top: 20px; height: 350px">
				<?php
				$key = md5('dt-invoices' . $model->id);
				echo $model->status == DtInvoices::DOC_NEW ?
					Html::a('Добавить/Изменить скан', ['images/index',
					'target' => 'dt-invoices/view',
					'owner' => $key,
					'owner_id' => $model->id])
					: '';
				?>
				<br>
				<?php $img = Html::img('/admin/' . Images::getLinkfile($key), ['class' => 'img-responsive', 'alt' => 'Отсутствует изображение']);
				echo Html::a($img, '#', ['data-toggle' => 'modal', 'data-target' => '#modalImg']);
				?>
			</div>
		</div>
	</div>

	<?= $model->status == DtInvoices::DOC_NEW ?
		Html::a('Выбрать устройства', '#', ['class' => 'btn btn-primary',
		'id' => 'linkModal',
		'data-target' => '/admin/dt-enquiry-devices/index-invoices?id=' . $model->id,
		'data-header' => 'Выбор устройства'])
		: '';
	?>
	<?php //echo $this->render('../dt-enquiry-devices/index_invoices', ['dt_invoice_id' => $model->id, 'dataProvider' => $dt_ed_provider, 'searchModel' => $dt_ed_search]) ?>
	<?= $this->render('../dt-invoice-devices/index', ['modelDoc' => $model, 'dataProvider' => $dt_id_provider, 'searchModel' => $dt_id_search]) ?>
	<?= $this->render('../dt-invoices-payment/index', ['modelDoc' => $model, 'dataProvider' => $dt_ip_provider, 'searchModel' => $dt_ip_search]) ?>
	<br>
	<?= $model->status == DtInvoices::DOC_NEW ?
		Html::a('Сохранить', ['dt-invoices/save', 'id' => $model->id], ['class' => 'btn btn-success'])
		: '';
	?>
</div>

