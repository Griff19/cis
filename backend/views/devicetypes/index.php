<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DevicetypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы устройств';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить тип устройства', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'synonyms',
            [
                'attribute' => 'comp',
                'format' => 'boolean',
                'filter' => ['Нет', 'Да'],
            ],
            ['attribute' => 'mac',
                'label' => 'MAC?',
                'format' => 'boolean',
                'filter' => ['Нет', 'Да'],
            ],
            ['attribute' => 'imei',
                'label' => 'IMEI?',
                'format' => 'boolean',
                'filter' => ['Нет', 'Да'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
