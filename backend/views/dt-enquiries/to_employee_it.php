<?php
/**
 * Представление встраивается на страницу site\it_index.php выводит список документов "Заявка на оборудование"
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-enquiries-index">

    <h3> Исполняемые заявки </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'id',
                'label' => 'Док №',
                'value' => function($model) {
                    return Html::a('Заявка №'. $model->id, ['dt-enquiries/view', 'id' => $model->id]);
                },
                'format' => 'raw'
            ],
            'create_date',
            'do_date',
            ['attribute' => 'employee_name',
                'value' => 'employee.snp'
            ],
            ['attribute' => 'status',
                'value' => 'statusString',
                'filter' => \backend\models\DtEnquiries::arrStatusString()
            ],
//            ['class' => 'yii\grid\ActionColumn',
//                'controller' => 'dt-enquiries'
//            ],
        ],
    ]); ?>
</div>