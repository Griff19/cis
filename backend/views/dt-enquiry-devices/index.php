<?php
/**
 * "Устройства в Заявке" встраивается в представление документа "Заявка на оборудование" (dt-enquiries/view)
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\DtEnquiryWorkplaces;
use backend\models\DtEnquiryDevices;
use backend\models\DtEnquiries;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\DtEnquiryDevicesSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $modelDoc \backend\models\DtEnquiries
 * @var $ids_wp[] integer массив идентификаторов рабочих мест
 */
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
            'contentOptions' => ['style' => 'padding-left: 1px; padding-right: 1px; text-align: center;'],
            'value' => function (DtEnquiryDevices $model) use ($modelDoc) {
                if ($modelDoc->status == 0) {
                    $str = $model->workplace_id ? $model->workplace_id : 0;
                    return Html::dropDownList('rm', $str,
                        ArrayHelper::map(DtEnquiryWorkplaces::arrWpIds($model->dt_enquiries_id), 'workplace_id', 'workplace_id'),
                        ['class' => 'form-control',
                            'id' => 'wp_sel',
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
        ['attribute' => 'statusString',
            'value' => function (DtEnquiryDevices $model) {
                $str = '';
                 if ($model->status == DtEnquiryDevices::RESERVED && $model->dtEnquiry->status == DtEnquiries::DTE_SAVED)
                    $str = '' . Html::a('(Установить)', ['set-device-on-wp', 'ded_id' => $model->id, 'wp_id' => $model->workplace_id],
                            ['title' => 'Пометить как установленное на выбраное РМ. Сменить статус']);
                return $model->statusString . $str;
            },
            'format' => 'raw',
        ],
        ['attribute' => 'dt_inv_id',
            'value' => function ($model){
                $res = null;
                if ($model->dt_inv_id) {
                    $res = Html::a('Cчет ИД ' . $model->dt_inv_id, ['dt-invoices/view', 'id' => $model->dt_inv_id]);
                }
                return $res;
            },
            'format' => 'raw',
        ],
        'note',
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'controller' => 'dt-enquiry-devices',
        ],
    ];
    if ($modelDoc->status == 1)
        unset($columns[10]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end();?>
</div>
