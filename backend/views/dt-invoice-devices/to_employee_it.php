<?php
/**
 * Таблица устройств, отраженных в документах "Счет"
 * Выводится в представлении site/employee-it (site\it_index.php)
 */
use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoice-devices-index">

    <h3>Устройства, требующие оплаты</h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'dt_invoices_id',
                'label' => 'Док. Счет',
                'value' => function ($model) {
                    return Html::a('Счет №' . $model->dt_invoices_id, ['dt-invoices/view', 'id' => $model->dt_invoices_id]);
                },
                'format' => 'raw'
            ],
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
                    $status = $model->status ? $model->statusString : '-';
                    if ($model->status == DtEnquiryDevices::AWAITING_PAYMENT) {
                        return $status . ' ' .Html::a('(Согласовать)', ['dt-enquiries/index-agree', 'id' => $model->dt_enquiries_id], ['title' => 'Согласовать оплату']);
                    } elseif ($model->status == DtEnquiryDevices::PAID) {
                        return $status . ' ' .Html::a('(Приходовать)', ['devices/create-from-doc',
                            'type_id' => $model->type_id,
                            'id_wp' => $model->dtEnquiryDevice ? $model->dtEnquiryDevice->workplace_id : null,
                            'idid' => $model->id
                        ], ['title' => 'Приходовать устройство']);
                    } else {
						return $status;
					}
                },
                'format' => 'raw',
                'filter' => DtEnquiryDevices::arrStatusString()
            ],
            ['attribute' => 'note',
                'format' => 'raw'
            ]
        ],
    ]); ?>
</div>