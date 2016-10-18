<?php
/**
 * Содержимое всплывающего/выпадающего окна
 * Используется в представлении документа "Счет" (dt-invoices/view) для выбора устройств, по которым формируется документ.
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\DtEnquiryWorkplaces;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiriyDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dt_invoice_id integer идентификатор документа счет */
?>
<div class="dt-enquiry-devices-index">
    <p>Устройства в заявках требующие покупки:</p>
    <?php Pjax::begin([
        'options' => ['id' => 'ded1'],
        //'enablePushState' => false
    ]);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'dt_enquiries_id',
            //'dt_def_dev_id',
            'workplace_id',
            ['attribute' => 'type_id',
                'value' => function ($model){
                    return \backend\models\DeviceType::getTitle($model->type_id);
                }
            ],
            'status',
            'note',
            ['class' => 'yii\grid\Column',
                'content' => function ($model) use ($dt_invoice_id) {
                    return Html::a('<span class = "glyphicon glyphicon-ok"></span>', ['dt-invoice-devices/create',
                        'dt_invoices_id' => $dt_invoice_id,
                        'dt_enquiries_id' => $model->dt_enquiries_id,
                        'type_id' => $model->type_id
                    ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end();?>
</div>
