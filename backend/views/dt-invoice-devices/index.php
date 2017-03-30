<?php
/**
 * Устройства в счете
 * Представление встраивается в представление документа "Счет" (dt-invoices/view)
 * отображает список устройств которые будут оплачиваться данным счетом
 */
use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoices;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerAssetBundle('backend\assets\AddInput');

?>
<div class="dt-invoice-devices-index">

    <h3>Устройства в счете:</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $modelDoc->status != DtInvoices::DOC_CLOSED ? Html::a('Добавить в счет', [
            'dt-invoice-devices/add',
            'dt_invoices_id' => $modelDoc->id,
        ], ['class' => 'btn btn-success']) : ''; ?>
    </p>
    <?php
    $cols = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'dt_invoices_id',
        ['attribute' => 'dt_enquiries_id',
            'value' => function ($model) {
                if ($model->dt_enquiries_id)
                    return Html::a('Заявка №' . $model->dt_enquiries_id . ' от ' . $model->dtEnquiry->create_date,
                        ['dt-enquiries/view', 'id' => $model->dt_enquiries_id],
                        ['title' => 'Открыть документ']
                        );
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
        ['attribute' => 'price',
            'contentOptions' => function($model) {
                return ['id' => 'd' . $model->id];
            },
            'value' => function ($model) {
                $price = $model->price;
                if ($price == 0)
                    $price = '0.00';
                /** @see \backend\assets\AddInput */
                $a = Html::a($price, 'javascript:', [
                        'class' => 'set-price',
                        'data-id' => $model->id,
                        'data-price' => $model->price,
                        'title' => 'Установить цену',
                ]);
                return $a;
            },
            'format' => 'raw',
        ],
        ['attribute' => 'status',
            'value' => function ($model) {
                $stts = $model->status ? DtEnquiryDevices::arrStatusString()[$model->status] : '-';
                if ($model->status ==  DtEnquiryDevices::PAID)
                    $a = Html::a('(Приходовать)', ['devices/create-from-doc',
                        'type_id' => $model->type_id,
                        'id_wp' => $model->dtEnquiryDevice ? $model->dtEnquiryDevice->workplace_id : null,
                        'idid' => $model->id
                    ], ['title' => 'Приходовать устройство']);
                else
                    $a = Html::a('(Сменить)', '', [
                    'class' => 'stat',
                    'title' => 'Сменить статус',
                    'onclick' => '$.post("/admin/dt-invoice-devices/set-status?id=' . $model->id . '")'
                ]);

                return $stts . ' ' . $a;
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
    ];
    if ($modelDoc->status == DtInvoices::DOC_CLOSED)
        array_pop($cols);

    \yii\widgets\Pjax::begin();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $cols,
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
