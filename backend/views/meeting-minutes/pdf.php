<?php
/**
 * Основное представление документа "Протокол встреч"
 * @see MeetingMinutesController::actionView()
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MeetingMinutes */


?>
<div class="meeting-minutes-view">

	<h1> Протокол встречи </h1>
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'doc_num',
			'doc_date:date',
		],
	]) ?>

	<?= $this->render('../mm-agenda/pdf', ['dataProvider' => $mma_provider]) ?>


	<?= $this->render('../mm-offer/pdf', ['dataProvider' => $mmo_provider]) ?>


	<?= $this->render('../mm-decision/pdf', ['dataProvider' => $mmd_provider]) ?>


	<?= $this->render('../mm-participants/pdf', ['dataProvider' => $mmp_provider]) ?>

</div>

