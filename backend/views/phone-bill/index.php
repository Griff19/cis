<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PhoneBillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета за телефон';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-bill-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p><?= Html::a('Загрузить файл...', ['cellnumbers/uploadform'], ['class' => 'btn btn-success'] )?></p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'employee_snp',
                'value' => function ($model){
                    return $model->employee ? $model->employee->snp : '-';
                },
            ],
            'number',
            'date',
            'subscription',
            'one_time',
            'online',
            'roaming',
            'cost',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
