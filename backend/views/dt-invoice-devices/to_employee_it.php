<?php

use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoice-devices-index">

    <h3>Устройства, требующие оплаты</h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => '\yii\grid\Column',
                'header' => 'Счет',
                'content' => function ($model) {
                    return Html::a('Счет №' . $model->dt_invoices_id, ['dt-invoices/view', 'id' => $model->dt_invoices_id]);
                }
            ],
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
            ['attribute' => 'status',
                'value' => function ($model) {
                    /** @var \backend\models\DtInvoiceDevices $model */
                    $stts = $model->status ? DtEnquiryDevices::arrStatusString()[$model->status] : '-';
                    //return $stts;
                    return Html::a($stts, ['dt-invoices-payment/create', 'id' => $model->dt_invoices_id], ['title' => 'Внести оплату']);
                },
                'format' => 'raw',
            ],
            'note',
        ],
    ]); ?>

</div>
