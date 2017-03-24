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

$this->title = 'Устройства';
$this->params['breadcrumbs'][] = $this->title;

$param = Yii::$app->request->queryParams;
$target = ArrayHelper::getValue($param, 'target');
$mess = '';
if ($target == 'dt-enquiry-devices/create2') $mess = 'Выберите устройство которое необходимо заменить';
//для работы фильтра определяем параметры основного запроса
if($target && $param) {
    $query = 'mode=' . ArrayHelper::getValue($param, 'mode') . '&'
        . 'target=' . ArrayHelper::getValue($param, 'target') . '&'
        . 'target_id=' . ArrayHelper::getValue($param, 'target_id') . '&'
        . 'id_wp=' . ArrayHelper::getValue($param, 'id_wp');
} else $query = null;
//var_dump($query);
?>
<div class="devices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= '<p>'. $mess .'</p>' ?>

    <p>
        <?php
        if (Yii::$app->user->can('admin')) {
            echo Html::a('Комплектующие автоматом', ['autocomp'], [
                    'class' => 'btn btn-success', 'data' => [
                            'confirm' => "Произвести автоматическую установку комплектуюдщих \r\n для одиноких системников?"
                ]]);
            echo Html::a('Добавить устройство', ['devices/create?'. $query], ['class' => 'btn btn-success']);
        }?>
        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Общий склад', ['devices/index?'. $query .'&DevicesSearch%5Bworkplace_id%5D=1'], ['class' => 'btn btn-default'])?>
		<?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Потерянные', ['devices/index?'. $query .'&DevicesSearch%5Bworkplace_id%5D=130'], ['class' => 'btn btn-default'])?>
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
                return $model->dev_comp ? 'Да' : 'Нет';
            },
            'filter' => ['Нет', 'Да']
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
            'content' => function ($model) use ($mode) {
                //if ($model->workplace) {
                //if($model->employee) $snp = $model->employee->snp;

                if ($model->workplace_id == 1 || $model->workplace_id == null) return '';
                if ($mode != 'def') {
                    return Html::img('/admin/img/man.png', ['style' => 'height:24px;',
                        'title' => $model->workplace_id . ' ' . $model->wp_title
                    ]);
                } else if ($mode == 'def'){
                    return Html::a(Html::img('/admin/img/man.png', ['style' => 'height:24px;']), ['workplaces/view', 'id' => $model->workplace_id], [
                        'title' => $model->getFullWorkplace(1)]);
                }
            }
        ],
        // активно при выборе устройства как комплектующее
        ['class' => Column::className(), //11
            'content' => function ($model) use ($target) {
                return Html::a(Html::img('/admin/img/ok.png', ['style' => 'height:24px;']),
                    //['devices/addcomp', 'id_dev' => $id_dev, 'id_comp' => $model->id, 'id_wp' => $id_wp]
                    [$target, 'id' => $model->id, 'param' => Yii::$app->request->queryString]
                );
            }
        ],
        ['class' => Column::className(), //12
            'content' => function ($model) {
                return Html::a(Html::img('/admin/img/view.png', ['style' => 'height:24px;']),
                    ['devices/view', 'id' => $model->id], ['title' => 'Просмотр и редактирование...']);
            }
        ],
    ];

    if ($mode == 'def') {
        //unset($cols[11]);
        unset($cols[11]);
    }
    if ($mode == 'wps') {
        //unset($cols[11]);
        unset($cols[12]);
    }
    if ($mode == 'dvs') {
        //unset($cols[11]);
        unset($cols[12]);
    }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function (Devices $model) {
            $res = '';

            if ($model->workplace_id == 127)
                $res = ['class' => 'danger'];
            elseif ($model->fake_device == 1)
                $res = ['class' => 'info'];
            return $res;
        },
        'columns' => $cols,
    ]); ?>

</div>
