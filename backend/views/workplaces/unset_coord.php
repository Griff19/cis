<?php
/**
 * Представление для отображения списка рабочих мест без установленных координат
 */

use yii\grid\GridView;
use yii\helpers\Html;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		['attribute' => 'id',
		 'options' => ['style' => 'width:30px'],
		 'filterOptions' => ['style' => 'padding: 8px 1px 0px 1px']
		],
		['attribute' => 'branch_id',
			'value' => 'branch.branch_title'
		],
		['attribute' => 'room_id',
			'value' => 'room.room_title'
		],
		['attribute' => 'workplaces_title',
			'value' => function($model){
				return Html::a($model->workplaces_title, ['workplaces/view', 'id' => $model->id]);
			},
			'format' => 'raw',
		],
		['attribute' => '_owner',
			'value' => function($model){
				if ($model->owner) return $model->owner[0]['snp'];
				else return '-';
			},
			'format' => 'raw'
		],
		['class' => 'yii\grid\Column',
			'header' => 'Координаты',
			'content' => function($model) {
				return Html::a('Установить коодинаты', ['coordinate/create', 'id_wp' => $model->id, 'mod' => 2]);
			}
		],
	]
]); ?>
