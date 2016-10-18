<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\DtEnquiryWorkplaces;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiriyDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDoc \backend\models\DtEnquiries */
/* @var $ids_wp[] integer массив идентификаторов рабочих мест */
?>
<div class="dt-enquiry-devices-index">
    <p>
        <?php
        if ($modelDoc->status == 0) {
            //щелкнув по кнопке можно выбрать устройство которое необходимо заменить
            //откроется список неисправных устройств
            echo Html::a('Добавить устройство со склада', ['devices/index-to-enquiry',
                    'mode' => 'dvs',
                    'target' => 'dt-enquiry-devices/create2',
                    'id_doc' => $modelDoc->id],
                    ['class' => 'btn btn-success']) . ' ';
            echo Html::a('Добавить новое устройство (по типу)', ['dt-enquiry-devices/create-new',
                'id_doc' => $modelDoc->id], ['class' => 'btn btn-success']);
        }?>
    </p>
    <?php Pjax::begin([
        'options' => ['id' => 'ded'. $modelDoc->id],
        //'enablePushState' => false
    ]);
    ?>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        'dt_enquiries_id',
        'dt_def_dev_id',
        ['attribute' => 'workplace_id',
            'value' => function ($model) use ($modelDoc) {
                if ($modelDoc->status == 0) {
                    $str = $model->workplace_id ? $model->workplace_id : 0;
                    return Html::dropDownList('rm', $str, ArrayHelper::map(DtEnquiryWorkplaces::arrWpIds($model->dt_enquiries_id), 'workplace_id', 'workplace_id'),
                        ['class' => 'form-control',
                            'title' => 'Выберите рабочее место для устройства...',
                            'onchange' => '$.post("/admin/dt-enquiries/set-device-wp?id='.$model->id.'&id_wp="+$(this).val())'
                        ]
                    );
                } else {
                    return $model->workplace_id;
                }
            },
            'format' => 'raw'
        ],
        'device_id',
        ['attribute' => 'type_id',
            'value' => function ($model){
                return \backend\models\DeviceType::getTitle($model->type_id);
            }
        ],
        'parent_device_id',
        'statusString',
        'note',
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'controller' => 'dt-enquiry-devices',
        ],
    ];
    if ($modelDoc->status == 1)
        unset($columns[9]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end();?>
</div>
