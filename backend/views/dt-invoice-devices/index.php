<?php

use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel app\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoice-devices-index">

    <h3>Устройства в счете:</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $modelDoc->status == DtInvoices::DOC_NEW ? Html::a('Добавить в счет', [
            'dt-invoice-devices/add',
            'dt_invoices_id' => $modelDoc->id,

        ], ['class' => 'btn btn-success']) : ''; ?>
    </p>
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'dt_invoices_id',
            ['attribute' => 'dt_enquiries_id',
                'value' => function ($model) {
                    if ($model->dt_enquiries_id)
                        return Html::a('Заявка №' . $model->dt_enquiries_id .' от '. $model->dtEnquiry->create_date, ['dt-enquiries/view', 'id' => $model->dt_enquiries_id]);
                    else
                        return '';
                },
                'format' => 'raw'
            ],
            ['attribute' => 'type_id',
                'value' => function ($model) {
                    return DeviceType::getTitle($model->type_id);
                }
            ],
            'price',
            //'status',
            ['attribute' => 'status',
                'value' => function ($model) {
                    $stts = $model->status ? DtEnquiryDevices::arrStatusString()[$model->status]: '-';
                    return Html::a($stts, '', [
                        'class' => 'stat',
                        'title' => 'Сменить статус',
                        'onclick' => '$.post("/admin/dt-invoice-devices/set-status?id=' . $model->id. '")'
                    ]);
                },
                'format' => 'raw',
            ],
            ['attribute' => 'note',
                'format' => 'raw'
            ],

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoice-devices',
                'template' => '{delete}'
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
