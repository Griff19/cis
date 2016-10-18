<?php
/**
 * Это представление встраивается в представление dt-enquiries\view.php
 * и отображает список списанных устройств в документе Заявка на оборудование
 * Из контроллера dt-defsheet-devices не доступно
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\Column;
use backend\models\DeviceType;
use yii\bootstrap\Modal;
use backend\models\DtDefsheets;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-defsheet-devices-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'workplace_id',
            'devices_id',
            ['class' => Column::className(),
                'header' => 'Тип устройства',
                'content' => function ($model) {
                    /* @var $model->devices \backend\models\Devices */
                    $type_id = $model->devices->type_id;
                    $title = DeviceType::getTitle($type_id);
                    return Html::a($title, ['/devices/view', 'id' => $model->devices_id]);
                },
                //'format' => 'raw'
            ],
            ['class' => Column::className(),
                'header' => 'Бренд',
                'content' => function ($model) {
                    return $model->devices->brand;
                }
            ],
            ['class' => Column::className(),
                'header' => 'Модель',
                'content' => function ($model) {
                    return $model->devices->model;
                }
            ],
            ['class' => Column::className(),
                'header' => 'SN',
                'content' => function ($model) {
                    return $model->devices->sn;
                }
            ],
            'reason',
            'status',
            ['class' => Column::className(),
                'content' => function ($model) use ($modelDoc){
                    if ($model->status > 1 || $modelDoc->status > 0)
                        $res = '';
                    else {
                        $type_id = $model->devices->type_id;
                        $title = DeviceType::getTitle($type_id);
                        $title = str_replace(" ", "+", $title);
                        $res = Html::a('<span class="glyphicon glyphicon-ok"></span>','#',
                            ['id' => 'linkModal' . $model->devices_id,
                                'data-target' => '/admin/devices/index?'
                                    .'DevicesSearch[dt_title]=' . $title
                                    .'&DevicesSearch[workplace_id]=1'
                                    .'&target=dt-enquiry-devices%2Fcreate2'
                                    .'&id_doc='.$modelDoc->id
                                    .'&dev_id='.$model->devices_id
                                    .'&id_def='. $model->id_def,
                                'data-header' => 'Подбор устройства',
                                'title' => 'Подобрать замену'
                            ]);
                    }
                    return $res;
                }
            ]
        ],
    ]); ?>
</div>
