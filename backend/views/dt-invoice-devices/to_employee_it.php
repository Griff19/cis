<?php
/**
 * Таблица устройств, отраженных в документах "Счет"
 * Выводится в представлении site/employee-it (site\it_index.php)
 */

use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoiceDevices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>

<div class="dt-invoice-devices-index">

    <h3>Устройства, требующие оплаты:
        <?= Html::a('Ведомость на согласование', ['dt-invoice-devices/pdf'],
            ['class' => 'btn btn-default', 'data-method' => 'post'])?>
    </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}',
        'rowOptions' => function ($model) {
            return ['class' => $model->dtEnquiry ? '' : 'danger'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'dt_invoices_id',
                'label' => 'Док. Счет',
                'value' => function ($model) {
                    /** @var $model DtInvoiceDevices */
                    return Html::a($model->dtInvoice->summary, ['dt-invoices/view', 'id' => $model->dt_invoices_id], ['data-method' => 'post']);
                },
                'format' => 'raw'
            ],
            ['attribute' => 'dt_enquiries_id',
                'value' => function ($model) {
                    if ($model->dt_enquiries_id && $model->dtEnquiry)
                        return Html::a('Заявка №' . $model->dt_enquiries_id .' от '. $model->dtEnquiry->create_date, ['dt-enquiries/view', 'id' => $model->dt_enquiries_id]);
                    else
                        return '<span class="text-danger">Заявка отсутствует</span>';
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
                    if ($model->status == DtEnquiryDevices::WAITING_AGREE) {
                        return $status . ' ' .Html::a('(Согласован!)', '#', [
                                'id' => 'linkModal',
                                'data-target' => '/admin/dt-invoices-payment/create?id='
                                    . $model->dt_invoices_id
                                    . '&is_modal=true&idid='
                                    . $model->id,
                                'data-header' => 'Фиксация согласованного платежа']);
                    } elseif ($model->status == DtEnquiryDevices::PAID) {
						return $status . ' ' . Html::a('(Приходовать)', ['devices/create-from-doc',
							'type_id' => $model->type_id,
							'id_wp' => $model->dtEnquiryDevice ? $model->dtEnquiryDevice->workplace_id : null,
							'idid' => $model->id
						], ['title' => 'Приходовать устройство']);
					} elseif ($model->status == DtEnquiryDevices::DEBIT) {
						return $status . ' ' .Html::a('(Закрыть заявку)', ['dt-enquiries/set-status',
							'id' => $model->dt_enquiries_id,
							'status' => \backend\models\DtEnquiries::DTE_COMPLETE],
							['title' => 'Закрыть заявку и скрыть строку из списка']);
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