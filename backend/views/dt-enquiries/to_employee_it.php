<?php
/**
 * Представление встраивается на страницу site\it_index.php (admin/site/employee-it) выводит список документов "Заявка на оборудование"
 */
use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\DtEnquiries;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-enquiries-index">

    <h3> Исполняемые заявки </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions' => function ($model) {
			return ['class' => $model->status == \backend\models\DtEnquiries::DTE_COMPLETE ? 'success' : ''];
		},
        'columns' => [
            ['attribute' => 'id',
                'label' => 'Док №',
                'value' => function($model) {
                    return Html::a('Заявка №'. $model->id, ['dt-enquiries/view', 'id' => $model->id], ['data-method' => 'post']);
                },
                'format' => 'raw'
            ],
            'create_date:date',
            'do_date:date',
            ['attribute' => 'employee_name',
                'value' => 'employee.snp'
            ],
            ['attribute' => 'status',
                'value' => 'statusString',
                'format' => 'raw',
                'filter' => DtEnquiries::arrStatusString()
            ],
            ['class' => '\yii\grid\Column',
                'options' => ['style' => 'width: 40px;'],
                'contentOptions' => ['style' => 'padding: 8px 0px 0px 1px'],
                'content' => function ($model) {
                    return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-plus'])
                        . Html::tag('span', '', ['class' => 'glyphicon glyphicon-file']),
                        ['dt-invoices/create', 'enquiry_id' => $model->id],
                        ['title' => 'Ввести новый счет']);
                }
            ],
        ],
    ]); ?>
</div>
