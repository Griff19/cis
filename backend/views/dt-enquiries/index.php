<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtEnquiriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на оборудование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-enquiries-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute' => 'employee_name',
                'value' => 'employee.snp'
            ],
            'create_date:date',
            'do_date:date',
            'create_time:datetime',
            //'workplace_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
