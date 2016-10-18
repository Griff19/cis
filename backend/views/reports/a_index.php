<?php

/**
 * @var $this \yii\web\View
 * @var $dp \yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Отчет';
$this->params['breadcrumbs'][] = $this->title;

echo '<br> Всего устройств: ' . $count;

echo GridView::widget([
    'dataProvider' => $dp,
    'filterModel' => $sm,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        //'type_id',
        [
            'attribute' => 'type_id',
            'label' => 'Код типа',

        ],
        //'title',
        [
            'attribute' => 'title',
            'label' => 'Тип устройства',
            'value' => function($dp){
                //var_dump($dp);
                return Html::a($dp['title'], ['reports/aemployee', 'type_id' => $dp['type_id']]);
            },
            'format' => 'raw'
        ],
        //'count',
        [
            'attribute' => 'count',
            'label' => 'Количество'
        ],

    ],
]);