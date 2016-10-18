<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Devices;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StorydeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moddev = $id_dev ? Devices::findOne($id_dev) : null;
$this->title = 'История изменений';
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => Url::to(['devices/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-device-index">
    <h4> История изменений </h4>
    <h1><?= ($moddev ? $moddev->deviceType->title : '') . ' ' . ($moddev ? $moddev->device_note : '') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'id_device',
            ['class' => '\yii\grid\Column',
                'header' => '№РМ',
                'content' => function($model) {
                    return $model->id_wp;
                },
            ],
            ['attribute' => 'id_wp',
                'value' => 'workplace.workplaces_title'
            ],
            'user.username',
            'date_up:datetime',
            'event',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
