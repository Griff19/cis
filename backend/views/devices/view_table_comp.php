<?php
/**
 * Вспомогательная таблица для вывода комплектующих в основную таблицу устройств
 *
 * @var $mode bool отображать колонку действий или нет
 */

use backend\models\DeviceType;
use yii\grid\GridView;
use yii\grid\Column;
use yii\helpers\Html;

$col1 = [
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'tableOptions' => ['class' => 'table table-bordered table-hover'],
	'showHeader' => false,
	'layout' => "{items}",
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		'id',
		['attribute' => 'type_id',
			'value' => function ($model){
				return '<b>'. Html::a(DeviceType::getTitle($model['type_id']), ['devices/view', 'id'=> $model['id']]) .'</b>';
			},
			'format' => 'raw'
		],
		'device_note',
		'brand',
		'model',
		'sn',
		'specification',
		'parent_device_id',
		['class' => Column::className(),
            'visible' => $mode,
			'content' => function ($moddev) use ($mode) {
				if (Yii::$app->user->can('admin'))
					return Html::a('',['devices/delfromwp', 'id' => $moddev['id'], 'id_wp' => $moddev['workplace_id']],['class' => 'cross']);
				else
					return '';
			}
		]
	],
];

echo GridView::widget($col1);
