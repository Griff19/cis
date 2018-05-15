<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TmpMovingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перемещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-moving-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать временное перемещение', ['/devices'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'device_id',
            ['attribute' => 'summary', 'value' => 'device.summary'],
            ['attribute' => 'workplace_from', 'value' => 'workplaceFrom.summary'],
            ['attribute' => 'workplace_where', 'value' => 'workplaceWhere.summary'],
            'user.employee.snp',
            ['class' => '\yii\grid\Column',
                'content' => function ($model) {
                    return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['devices/addtowp', 'id' => $model->device_id, 'id_wp' => $model->workplace_where]);
                }
            ],
            // 'status',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
