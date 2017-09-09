<?php
/**
 * Выводит таблицу устройств которые можно выбрать на складе для установки на рабочее место
 * Представление загружается в модальное окно на странице dt-enquiries\view
 * при нажатии соответствующей кнопки в таблице "Списанных с рабочих мест устройств"
 */
use backend\models\DeviceType;
use backend\models\Devices;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Устройства на складе';
$this->params['breadcrumbs'][] = $this->title;

$param = Yii::$app->request->queryParams;

$target = ArrayHelper::getValue($param, 'target');
$target_id = ArrayHelper::getValue($param, 'id_doc');
$ds = ArrayHelper::getValue($param, 'DevicesSearch');

$dev_id = ArrayHelper::getValue($param, 'dev_id');
$id_def = ArrayHelper::getValue($param, 'id_def');
$mess = '';

if ($target == 'dt-enquiry-devices/create2') $mess = 'Выбраны устройства с типом <b>'. $ds['dt_title'] . '</b>';
//var_dump($query);
?>
<div class="devices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= '<p>'. $mess .'</p>' ?>
    <?php

    echo '<p>Если в списке нет подходящих устройств нажмите кнопку "Пропустить" для закупки нового.</p>';
    /** @var DeviceType $type код типа списанного устройства */
    $type = DeviceType::findOne(['title' => $ds['dt_title']]);
    echo Html::a('Пропустить', ['dt-enquiry-devices/create-new',
        'id_doc' => $target_id,
        'param' => 'type='. $type->id
            .'&dev_id='. $dev_id
            .'&id_def='. $id_def
    ], ['class' => 'btn btn-primary']);

    $cols = [
        'id',

        ['class' => Column::className(),
            'header' => 'Парам.',
            'content' => function ($model) {
                return $model->brand .', '.$model->model.', '.$model->sn.', '.$model->imei1;
            }
        ],

        'specification',

        'device_note',
        ['class' => Column::className(),
            'content' => /**
             * @param $model Devices
             * @return string
             * $target_id идентификатор DtEnquiries
             * $dev_id идентификатор списанного устройства
             * $id_def идентификатор строки таблицы списанных устройств
             */
                function ($model) use ($target_id, $dev_id, $id_def){
                return Html::a('<span class="glyphicon glyphicon-ok"></span>',
                    ['dt-enquiry-devices/create2', 'id' => $model->id, 'id_doc' => $target_id, 'dev_id' => $dev_id, 'id_def' => $id_def]);
                }
        ]
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions' => function (Devices $model) {
            $res = null;

            if ($model->fake_device == 2)
                $res = ['class' => 'danger'];
            elseif ($model->fake_device == 1)
                $res = ['class' => 'info'];
            return $res;
        },
        'columns' => $cols,
    ]);

    ?>

</div>
