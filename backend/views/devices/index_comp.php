<?php

use backend\models\DeviceType;
use backend\models\Devices;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комплектующие устройства';
$this->params['breadcrumbs'][] = $this->title;

$param = Yii::$app->request->queryParams;
$target = ArrayHelper::getValue($param, 'target');

$mess = 'Выберите комплектующее:';
//для работы фильтра определяем параметры основного запроса
if($target && $param) {
    $query = 'mode=' . ArrayHelper::getValue($param, 'mode') . '&'
        . 'target=' . ArrayHelper::getValue($param, 'target') . '&'
        . 'target_id=' . ArrayHelper::getValue($param, 'target_id') . '&'
        . 'id_wp=' . ArrayHelper::getValue($param, 'id_wp') . '&'
        . 'id_dev=' . ArrayHelper::getValue($param, 'id_dev');
} else $query = null;

?>
<div class="devices-index-comp">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= '<p>'. $mess .'</p>' ?>

    <p>
        <?= Html::a('Добавить устройство', ['devices/create?'. $query], ['class' => 'btn btn-success']) ?>

        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Общий склад', ['devices/index?'. $query .'&DevicesSearch%5Bworkplace_id%5D=1'], ['class' => 'btn btn-default'])?>
        <?= Html::a(Html::img('/admin/img/cross.png', ['style' => 'height: 14px; margin: 0px 1px 3px 0px;']) . ' Сбросить фильтр', ['devices/index?' . $query], ['class' => 'btn btn-default', 'style' => 'float:right;']) ?>
    </p>

    <?php
    $cols = [
        'id',
        ['attribute' => 'dt_title',
            'filter' => ArrayHelper::map(DeviceType::arrDevType(), 'title', 'title'),
            'value' => function($model) {
                return Html::a(DeviceType::getTitle($model->type_id), ['view', 'id' => $model->id]);
                //return Html::a($model->dt_title, ['view', 'id' => $model->id]);
            },
            'format' => 'raw'
        ],
        ['attribute' => 'dev_comp',
            'value' => function ($model) {
                return $model->deviceType['comp'] ? 'Да' : 'Нет';
            },
            'filter' => [false => 'Нет', true => 'Да']
        ],
        'brand',
        'model',
        'sn',
        'specification',
        'imei1',
        ['attribute' => 'parent_device_id',
            'value' => function ($model) {
                return Html::a($model->parent_device_id, ['devices/view', 'id' => $model->parent_device_id]);
            },
            'format' => 'raw'
        ],
        'device_note',
        ['class' => Column::className(),
            'content' => function ($model) {
                if ($model->workplace_id == 1 || $model->workplace_id == null) return '';
                else return Html::a('<span class="glyphicon glyphicon-user">', ['workplaces/view', 'id' => $model->workplace_id], [
                    'title' => $model->getFullWorkplace(1)]);
            }
        ],
        // активно при выборе устройства как комплектующее
        ['class' => Column::className(), //11
            'content' => function ($model) use ($target) {
                return Html::a('<span class="glyphicon glyphicon-ok"></span>',
                    //['devices/addcomp', 'id_dev' => $id_dev, 'id_comp' => $model->id, 'id_wp' => $id_wp]
                    [$target, 'id' => $model->id, 'param' => Yii::$app->request->queryString]
                );
            }
        ],
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function (Devices $model) {
            $res = null;

            if ($model->workplace_id == 127)
                $res = ['class' => 'danger'];
            elseif ($model->fake_device == 1)
                $res = ['class' => 'info'];
            return $res;
        },
        'columns' => $cols,
    ]); ?>

</div>
