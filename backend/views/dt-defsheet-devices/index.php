<?php
/**
 * Это представление встраивается в dt-defsheets\view.php
 * и отображает список устройств в документе Акт списания
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\Column;
use backend\models\DeviceType;
use yii\bootstrap\Modal;
use backend\models\DtDefsheets;

/* @var $modelDoc \backend\models\DtDefsheets */
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtDefsheetDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Modal::begin([
//    'header' => '<h4>Укажите причину списания</h4>',
//    'id' => 'modal2',
//    'size' => 'modal-md'
//]);
//echo '<div id = modalContent2></div>';
//Modal::end();

?>
<div class="dt-defsheet-devices-index">
    <p>

        <?php
            if ($modelDoc->status == 0)
                echo Html::a('Добавить устройство', ['devices/index', 'mode' => 'dvs', 'target' => 'dt-defsheet-devices/create', 'target_id' => $modelDoc->id], ['class' => 'btn btn-success'])
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            ['class' => Column::className(),
//                'header' => '№ РМ',
//                'content' => function($model) {
//                    return $model->devices->workplace_id;
//                }
//            ],
            //'dt_defsheets_id',
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
            ['attribute' => 'reason',
                'value' => function ($model) use ($modelDoc){
                    if ($model->reason || $modelDoc->status > DtDefsheets::STATUS_NEW)
                        $txt = $model->reason;
                    else
                        $txt = 'Указать причину';
                    return Html::a($txt, '#',
                        ['id' => 'linkModal2',
                            'data-target' => '/admin/dt-defsheet-devices/update?dt_defsheets_id=' . $model->dt_defsheets_id
                            . '&devices_id=' . $model->devices_id,
                            'data-header' => 'Укажите причину'
                        ]);
                },
                'format' => 'raw'
            ],
            'statusString',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'controller' => 'dt-defsheet-devices'
            ],
        ],
    ]); ?>

</div>
