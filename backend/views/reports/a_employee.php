<?php
/**
 * User: ivan
 * Date: 09.02.2016
 * Time: 12:31
 *
 * @var $this yii\web\View
 * @var $dp yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\grid\Column;
use backend\models\Devices;

$this->title = '' . $title;
$this->params['breadcrumbs'][] = ['label' => 'Отчет', 'url' => ['reports/aindex']];
$this->params['breadcrumbs'][] = $this->title;

echo 'Отчет по устройству';
echo '<h1>' . $title . '</h1>';

echo GridView::widget([
    'dataProvider' => $dp,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        //'branch_id',
        [
            'attribute' => 'branch_id',
            'label' => 'Подразделение'
        ],
        //'workplace_id',
//        [
//            'attribute' => 'workplace_id',
//            'label' => 'Код РМ'
//        ],
        //'workplaces_title',
        [
            'attribute' => 'workplaces_title',
            'label' => 'Рабочее место',
            'value' => function($dp) {
                return Html::a($dp['workplaces_title'], ['workplaces/view', 'id' => $dp['workplace_id']]);
            },
            'format' => 'raw'
        ],
        ['class' => Column::className(),
            'header' => 'Кол-во',
            'content' => function($dp) use ($type_id){
                return Devices::getCountOnWp($type_id, $dp['workplace_id']);
            }
        ],
        //'snp',
        [
            'attribute' => 'snp',
            'label' => 'Ответственное лицо'
        ],
        //'job_title',
        [
            'attribute' => 'job_title',
            'label' => 'Должность'
        ],
        //'date'
        [
            'attribute' => 'date',
            'label' => 'Дата'
        ]
    ],
]);