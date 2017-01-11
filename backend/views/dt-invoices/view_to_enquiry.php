<?php
/**
 * Представление документа "Счет"
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use backend\models\Images;
use backend\models\DtInvoices;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoices */

$this->registerJs('
	$("img").error(function () {
        $(this).attr("src", "../img/noimage.jpg");
    });
');

$this->title = 'Документ №' . $model->doc_number;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="dt-invoices-view">

	<div class="row">
		<div class="col-lg-6">
			<h1> <?= Html::encode($this->title) ?></h1>
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

	<?php //echo $this->render('../dt-invoice-devices/index', ['modelDoc' => $model, 'dataProvider' => $dt_id_provider, 'searchModel' => $dt_id_search]) ?>
	<h4>Список устройств:</h4>
	<?= GridView::widget([
		'dataProvider' => $dt_id_provider
	]); ?>

	<?php //echo $this->render('../dt-invoices-payment/index', ['modelDoc' => $model, 'dataProvider' => $dt_ip_provider, 'searchModel' => $dt_ip_search]) ?>
	<h4>Оплаты:</h4>
	<?= GridView::widget([
		'dataProvider' => $dt_ip_provider
	])?>

</div>