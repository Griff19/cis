<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Employees */

$this->title = $model->snp;
if ($mode == 'start')
;
else $this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'surname',
            //'name',
            //'patronymic',
            'job_title',
            //'employee_number',
            //'unique_1c_number',
            'branch.branch_title',
            'statusStr'
        ],
    ]) ?>

</div>
<div class="cell-numbers-index">
    <h4> Сотовые номера: </h4>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        //'id',
        'cell_number',
        //'employee_id',
        [
            'attribute' => 'status',
            'value' => function ($numbers){
                if ($numbers->status == 1) return 'Основной';
                else return '';
            },
            'format' => 'raw'
        ]
    ];

    echo GridView::widget([
        'dataProvider' => $cellProvider,
        //'filterModel' => $cellSearch,
        'layout' => "{items}",
        'rowOptions' => function ($numbers){
            /** @var $numbers \backend\models\CellNumbers */
            return $numbers->status == 1 ? ['class' => 'success'] : null;
        },
        'columns' => $columns,
    ]); ?>
    <br>
</div>
<div class="emails-index">
    <h4> Адреса электронной почты: </h4>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        //'id',
        [
            'attribute' => 'email_address',
            'value' => 'email_address'
        ],
        //'employee_id',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return $model->status == 1 ? 'Основной' : '';
            }
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $emailProvider,
        //'filterModel' => $emailSearch,
        'layout' => "{items}",
        'rowOptions' => function ($model) {
            return $model->status == 1 ? ['class' => 'success'] : null;
        },
        'columns' => $columns
    ]); ?>
    <br>
</div>
<div class="voip-index">
    <h4> Внутренние номера: </h4>
    <?= GridView::widget([
        'dataProvider' => $model->getVoipProvider($model->id),
        //'filterModel' => $emailSearch,
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'voip_number',
                'header' => 'Внутренний номер'
            ],
            //'voip_number',
            [
                'attribute' => 'workplaces_title',
                'header' => 'Описание рабочего места',
                'value' => function ($arr) {
                    return $arr['workplaces_title'];
                },
                'format' => 'raw'
            ]
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>