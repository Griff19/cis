<?php
/**
 * Основное представление документа "Протокол встреч"
 * @see MeetingMinutesController::actionView()
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MeetingMinutes */

$this->title = 'Протокол встречи №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Протоколы встреч', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-minutes-view">
	<div class="row">
		<div class="col-lg-6">
			<h1><?= Html::encode($this->title) ?>

				<?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => 'Уверенны что хотите удалить этот документ?',
						'method' => 'post',
					],
				]) ?>
			</h1>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'doc_num',
					'doc_date:date',
				],
			]) ?>

			<?= $this->render('../mm-agenda/index', ['modelDoc' => $model, 'dataProvider' => $mma_provider, 'searchModel' => $mma_search]) ?>
			<?= $this->render('../mm-agenda/_form', ['model' => $mma_model]) ?>
		</div>
		<div class="col-lg-6">
			<?= $this->render('../mm-participants/index', ['modelDoc' => $model, 'dataProvider' => $mmp_provider, 'searchModel' => $mmp_search])?>
			<?= $this->render('../mm-participants/_form', ['model' => $mmp_model]) ?>
		</div>
	</div>


	<?= $this->render('../mm-offer/index', ['modelDoc' => $model, 'dataProvider' => $mmo_provider, 'searchModel' => $mmo_search]) ?>
	<?= $this->render('../mm-offer/_form', ['model' => $mmo_model]) ?>

	<?= $this->render('../mm-decision/index', ['modelDoc' => $model, 'dataProvider' => $mmd_provider, 'searchModel' => $mmd_search]) ?>
	<?= $this->render('../mm-decision/_form', ['model' => $mmd_model]) ?>
</div>

<?php
$this->registerJsFile('/admin/js/check_keys.js');
?>
