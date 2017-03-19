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
    <h3>Устройства, требующие покупки:</h3>
    <?php Pjax::begin();
    ?>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'dt_enquiries_id',
            'header' => 'Заявка',
            'value' => function ($model) {
                return Html::a('Заявка №' . $model->dt_enquiries_id . ' от ' . $model->dtEnquiry->create_date, ['dt-enquiries/view', 'id' => $model->dt_enquiries_id]);
            },
            'format' => 'raw'
        ],
        'dt_def_dev_id',
        ['attribute' => 'workplace_id',
            'value' => function ($model) {
                return $model->workplace_id;
            },
            'format' => 'raw'
        ],
        'device_id',
        ['attribute' => 'type_id',
            'value' => function ($model){
                return \backend\models\DeviceType::getTitle($model->type_id);
            }
        ],
        //'parent_device_id',
        ['attribute' => 'statusString',
            'value' => function ($model) {
                return Html::a($model->statusString, ['dt-invoices/create', 'id' => $model->dt_enquiries_id],
                    ['title' => 'Создать документ "Счет"', 'data-method' => 'post']);
            },
            'format' => 'raw'
        ],
        'note',
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end();?>
</div>
