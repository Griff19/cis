<?php

use backend\models\DeviceType;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DtInvoiceDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoice-devices-index">

    <h3>Устройства в счете:</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить в счет', ['dt-invoice-devices/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'dt_invoices_id',
            ['attribute' => 'dt_enquiries_id',
                'value' => function ($model) {
                    return Html::a('Заявка №' . $model->dt_enquiries_id .' от '. $model->dtEnquiry->create_date, ['dt-enquiries/view', 'id' => $model->dt_enquiries_id]);
                },
                'format' => 'raw'
            ],
            ['attribute' => 'type_id',
                'value' => function ($model) {
                    return DeviceType::getTitle($model->type_id);
                }
            ],
            'price',
            'status',
            'note',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoice-devices'
            ],
        ],
    ]); ?>
</div>
