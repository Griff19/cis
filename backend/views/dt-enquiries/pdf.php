<?php
/**
 * Представление pdf-версии документа "Заявка на оборудование" (dt-enquiries/pdf)
 */
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $model \backend\models\DtEnquiries
 */

?>

<h3> Заявка на оборудование №<?= $model->id ?> от <?= $model->createDate ?></h3>

<?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
    'attributes' => [
        'id',
        ['attribute' => 'employee_id',
            'value' => $model->employee ? $model->employee->snp : '',
        ],
        'create_date:date',
        'do_date:date',
        'create_time:datetime',
        //'workplace_id',
//                    ['label' => 'Ответственный',
//                    'value' => $model->ownerWP->snp],
        'memo:boolean',
        'statusString'
        ]
    ])?>
<h4>Рабочие места:</h4>
<?= GridView::widget([
    'dataProvider' => $wpProvider,
    'layout' => '{items}',
    'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
    //'tableOptions' => ['class' => 'table table-striped table-condensed'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        //'dt_enquiries_id',
        ['attribute' => 'workplace_id',
            'header' => 'РМ №'
        ],
        'workplace.workplaces_title',
        ['class' => '\yii\grid\Column',
            'header' => 'Ответственный',
            'content' => function($model) {
                return $model->owner->snp;
            }
        ],
    ]
])?>
<h4>Устройства заказываемые по заявке:</h4>
<?= GridView::widget([
    'dataProvider' => $dedProvider,
    'layout' => '{items}',
    'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        //'dt_enquiries_id',
        //'dt_def_dev_id',
        ['attribute' => 'workplace_id', 'header' => 'РМ №'],
        ['attribute' => 'device_id', 'header' => 'Ид уст.'],
        ['attribute' => 'type_id',
            'header' => 'Тип устройства',
            'value' => function ($model){
                return \backend\models\DeviceType::getTitle($model->type_id);
            }
        ],
        //'parent_device_id',
        'statusString',
        ['attribute' => 'dt_inv_id',
            'header' => 'Ид счета',
            'value' => function ($model){
                $res = null;
                if ($model->dt_inv_id) {
                    $res = 'Cчет ИД ' . $model->dt_inv_id;
                }
                return $res;
            },
            'format' => 'raw',
        ],
        ['attribute' => 'note', 'header' => 'Заметка']
    ]
])
?>
