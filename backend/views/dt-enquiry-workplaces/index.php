<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiryWorkplacesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDoc \backend\models\DtEnquiries */

?>
<div class="dt-enquiry-workplaces-index">

    <p>
        <?php if($modelDoc->status == 0)
            echo Html::a('Добавить рабочее место',
            ['workplaces/index', 'mode' => 'sel', 'target' => 'dt-enquiry-workplaces/create', 'target_id' => $modelDoc->id],
            ['class' => 'btn btn-success']);
        ?>
    </p>

    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        'dt_enquiries_id',
        'workplace_id',
        ['class' => '\yii\grid\Column',
            'header' => 'Ответственный',
            'content' => function($model) {
                return $model->owner->snp;
            }
        ],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            //'controller' => '',
            'buttons' => [
                'delete' => function ($url, $model, $key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',[
                        'dt-enquiry-workplaces/delete', 'dt_enquiries_id' => $model->dt_enquiries_id, 'workplace_id' => $model->workplace_id
                    ],[
                        'data-confirm' => 'Хотите удалить Рабочее место №'.$model->workplace_id
                            .'? Вместе с ним потеряются и устройства, связанные с этим Местом.',
                        'data-method' => 'post'
                    ]);
                }
            ]
        ],
    ];
    if ($modelDoc->status == 1)
        unset($columns[4]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
