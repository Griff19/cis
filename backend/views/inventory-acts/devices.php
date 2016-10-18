<?php

use backend\models\InventoryActs;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\Column;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InventoryActsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $id_wp integer */
/* @var $modelDoc backend\models\InventoryActs */

//$this->title = 'Устройства на рабочем месте';
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="inventory-acts-devices">

    <h1><?= 'Устройства на рабочем месте:' ?></h1>

    <?php
    \yii\widgets\Pjax::begin([
        'options' => ['id' => $modelDoc->id],
        'enablePushState' => false
    ]);
    $arr = $modelDoc->arrayDevIDinTb();
    //var_dump($arr);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        //'tableOptions' => ['class' => 'table-pdf'],
        'rowOptions' => function ($model) use ($arr) {
            $value = ArrayHelper::getValue($arr, $model['id']);
            if ($value == InventoryActs::DEVICE_OK) {
                return ['class' => 'success'];
            } elseif ($value == InventoryActs::MISSING_DEV) {
                return ['class' => 'danger'];
            } elseif ($value == InventoryActs::ADDITION_DEV || $value == InventoryActs::REPLACE_DEV) {
                return ['class' => 'info'];
            } else {
                return '';
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'sort',
            'id',
            ['attribute' => 'type_id',
                'value' => function ($model) {
                    return \backend\models\DeviceType::getTitle($model['type_id']);
                }
            ],
            'device_note',
            //'workplace_id',
            'brand',
            'model',
            'sn',
            'specification',
            'parent_device_id',

            ['class' => Column::className(),
                'content' => function ($model) use ($modelDoc, $id_wp) {
                    if ($modelDoc->status == 0)
                    return Html::a('<span class="glyphicon glyphicon-ok"></span>',[
                        'inventory-acts/create-tb', 'act_id' => $modelDoc->id, 'dev_id' => $model['id'], 'id_wp' => $id_wp, 'status' => InventoryActs::DEVICE_OK
                    ],['title' => 'Отметить строку как просмотренную']);
                },
                //'format' => 'raw'
            ],
            ['class' => Column::className(),
                'content' => function ($model) use ($modelDoc, $id_wp) {
                    if ($modelDoc->status == 0)
                    return Html::a('<span class="glyphicon glyphicon-eye-close"></span>',[
                        'inventory-acts/create-tb', 'act_id' => $modelDoc->id, 'dev_id' => $model['id'], 'id_wp' => $id_wp, 'status' => InventoryActs::MISSING_DEV
                    ],['title' => 'Пропало!']);
                },
                //'format' => 'raw',
            ],
            ['class' => Column::className(),
                'content' => function ($model) use ($modelDoc, $id_wp) {
                    if ($modelDoc->status == 0)
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',[
                        'devices/update', 'id' => $model['id'], 'id_wp' => $id_wp, 'target' => 'inventory-acts/create-tb', 'act_id' => $modelDoc->id
                    ], ['title' => 'Редактировать', 'data-method' => 'POST']);
                },
                //'format' => 'raw'
            ],
        ],
    ]);
    ?>
    <?php \yii\widgets\Pjax::end(); ?>
    <p>
        <?php
        if ($modelDoc->status == 0)
            echo Html::a('Добавить устройство', ['devices/create',
                'target' => 'new-dev', 'act_id' => $modelDoc->id, 'id_wp' => $id_wp],
                ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
    </p>

</div>

