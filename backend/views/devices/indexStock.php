<?php

use backend\models\DeviceType;
use backend\models\Devices;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Устройства на складе';
$this->params['breadcrumbs'][] = $this->title;

$param = Yii::$app->request->queryParams;
$target = ArrayHelper::getValue($param, 'target');
$mess = '';
if ($target == 'dt-enquiry-devices/create2') $mess = 'Выберите устройство которое необходимо установить';
if($target && $param) $query = Yii::$app->request->queryString; else $query = null;
//var_dump($query);
?>
<div class="devices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= '<p>'. $mess .'</p>' ?>

    <?php

    $cols = [
        'id',
        ['attribute' => 'dt_title',
            'filter' => ArrayHelper::map(DeviceType::arrDevType(), 'title', 'title'),
            'value' => function($model) {
                return Html::a(DeviceType::getTitle($model->type_id), ['view', 'id' => $model->id]);
            },
            'format' => 'raw'
        ],
        ['attribute' => 'dev_comp',
            'value' => function ($model) {
                return $model->dev_comp ? 'Да' : 'Нет';
            },
            'filter' => ['Нет', 'Да']
        ],
        'brand',
        'model',
        'sn',
        'specification',
        'imei1',
        ['attribute' => 'parent_device_id',
            'value' => function ($model) {
                return Html::a($model->parent_device_id, ['devices/view', 'id' => $model->parent_device_id]);
            },
            'format' => 'raw'
        ],
        'device_note',
        ['class' => Column::className(), //11
            'content' => function ($model) use ($target) {
                /** Кнопка выбора устройства в строке
                 * идентификатор устройства передается в таргет-контроллер
                 */
                return Html::a(Html::img('/admin/img/ok.png', ['style' => 'height:24px;']),
                    [$target, 'id' => $model->id, 'param' => Yii::$app->request->queryString]
                );
            }
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function (Devices $model) {
            $res = '';

            if ($model->fake_device == Devices::DEVICE_FAKE)
                $res = ['class' => 'info'];
            else if ($model->fake_device == Devices::DEVICE_RESERVED)
                $res = ['class' => 'danger'];
            return $res;
        },
        'columns' => $cols,
    ]); ?>

</div>
