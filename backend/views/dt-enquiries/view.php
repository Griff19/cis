<?php

use backend\models\Images;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiries */
/* @var $wpProvider \yii\data\ActiveDataProvider */

$this->title = 'Заявка №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки на оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//окно для вывода увеличенного изображения
Modal::begin([
	'id' => 'modalImg',
	'size' => 'modal-lg'
]);
$key = md5('dt-enquiries' . $model->id);
echo Html::img('/admin/' . Images::getLinkfile($key), ['style' => 'width: 100%', 'alt' => 'Отсутствует изображение']);
Modal::end();
//окно для вывода views/devices/to_enquire
Modal::begin([
	'header' => '<h4 id = modalHeader></h4>',
	'id' => 'modal',
	'size' => 'modal-lg'
]);
echo '<div id="modalContent"></div>';
Modal::end();
?>

<div class="dt-enquiries-view">
	<div class="row">
		<div class="col-sm-6">
			<h1><?= Html::encode($this->title) ?></h1>
			<p>
				<?php
				if ($model->status == 0) {
					echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
					echo Html::a('Удалить', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Хотите удалить документ ' . $this->title . '?',
							'method' => 'post',
						],
					]);
				} else {
					echo Html::a('Ввести счет', ['dt-invoices/create'], ['class' => 'btn btn-primary']) . ' ';
					echo Html::a('Согласовать', ['index-agree', 'id' => $model->id], ['class' => 'btn btn-default']);
				} ?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					['attribute' => 'employee_id',
						'value' => $model->employee ? $model->employee->snp : '',
					],
					'create_date',
					'do_date',
					'create_time:datetime',
					//'workplace_id',
//                    ['label' => 'Ответственный',
//                    'value' => $model->ownerWP->snp],
					'memo:boolean',
					'statusString'
				],
			]) ?>
		</div>
		<div class="col-sm-6">
			<div class="img-thumbnail img-block" style="margin-top: 20px; height: 350px">
				<?php
				$key = md5('dt-enquiries' . $model->id);
				echo Html::a('Добавить/Изменить скан', ['images/index',
					'target' => 'dt-enquiries/view',
					'owner' => $key,
					'owner_id' => $model->id]);
				//echo Html::a('Удалить скан', '', ['class' => 'btn btn-danger', 'style' => 'float: right']);
				?>
				<br>
				<?php $img = Html::img('/admin/' . Images::getLinkfile($key), ['class' => 'img-responsive', 'alt' => 'Отсутствует изображение']);
				echo Html::a($img, '#', ['data-toggle' => 'modal', 'data-target' => '#modalImg']);
				?>
			</div>
		</div>
	</div>
	<br>

	<h3>Список рабочих мест:</h3>
	<!--    Список рабочих мест-->
	<?= $this->render('../dt-enquiry-workplaces/index', ['modelDoc' => $model, 'dataProvider' => $wpProvider]) ?>


	<!--    Список списаных с рабочих мест устройств-->
	<?php
	$arr_ids_wp = [];
	foreach ($wpProvider->models as $wpModel) {
		/* @var $wpModel \backend\models\DtEnquiryWorkplaces */
		$arr_ids_wp[] = $wpModel->workplace_id;
		//var_dump($arr_ids_wp);
	}
	if ($model->status == 0) {
		echo '<h3>Список устройств списанных с указанных рабочих мест:</h3>';
		echo $this->render('../dt-defsheet-devices/to_enquire', [
			'modelDoc' => $model,
			'dataProvider' => \backend\models\DtDefsheetDevices::Devices127($arr_ids_wp),
		]);
	}
	?>

	<h3>Список устройств в заявке:</h3>
	<!--    Список запрашиваемых устройств-->
	<?= $this->render('../dt-enquiry-devices/index', ['modelDoc' => $model, 'dataProvider' => $dedProvider, 'searchModel' => $dedSearch, 'ids_wp' => $arr_ids_wp]) ?>
	<?php
	if ($model->status == 0)
		echo Html::a('Сохранить заявку', ['save', 'id' => $model->id], ['class' => 'btn btn-success']);
	else
		echo Html::a('Отменить сохранение', ['un-save', 'id' => $model->id], ['class' => 'btn btn-success'])
	?>
</div>
