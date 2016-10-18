<?php

use yii\grid\GridView;
use yii\grid\Column;
use backend\models\DeviceType;
use yii\helpers\Html;
/**
 * @var $model \backend\models\DtDefsheets
 * @var $dddProvider \yii\data\ActiveDataProvider
 */
$date = new DateTime($model->date_create);
?>

<h3>Акт списания №<?= $model->id?> от <?= $date->format('d.m.Y')?></h3>
<p> Списываемые устройства: </p>
<?= GridView::widget([
    'dataProvider' => $dddProvider,
    'layout' => '{items}',
    'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 9px'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
//            ['class' => Column::className(),
//                'header' => '№ РМ',
//                'content' => function($model) {
//                    return $model->devices->workplace_id;
//                }
//            ],
        //'dt_defsheets_id',
        'workplace_id',
        'devices_id',
        ['class' => Column::className(),
            'header' => 'Тип устройства',
            'content' => function ($model) {
                /* @var $model->devices \backend\models\Devices */
                $type_id = $model->devices->type_id;
                return DeviceType::getTitle($type_id);
                //return Html::a($title, ['/devices/view', 'id' => $model->devices_id]);
            },
            //'format' => 'raw'
        ],
        ['class' => Column::className(),
            'header' => 'Бренд',
            'content' => function ($model) {
                return $model->devices->brand;
            }
        ],
        ['class' => Column::className(),
            'header' => 'Модель',
            'content' => function ($model) {
                return $model->devices->model;
            }
        ],
        ['class' => Column::className(),
            'header' => 'SN',
            'content' => function ($model) {
                return $model->devices->sn;
            }
        ],
        'reason',
        //'status',
    ],
])?>

<p>Ответственный ___________ <?= $model->employee->snp ?></p>
<p>Списание провел ___________ <?= $model->actor->snp ?></p>
