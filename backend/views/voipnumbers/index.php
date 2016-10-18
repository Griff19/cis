<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VoipnumbersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Внутренние номера';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voip-numbers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать номер', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Загрузить из файла', ['uploadform'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        $cols = [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'voip_number',
            ['attribute' => 'secret',
                'value' => function ($model) {
                    return Html::a($model->secret ? '* * *' : '', '', ['title' => $model->secret]);
                },
                'format' => 'raw'
            ],
            'description',
            'context',
            // 'device_id',
            //'status',
            ['class' => Column::className(),
                'content' => function ($model) use ($id_wp) {
                    return Html::a('Выбрать', ['voipnumbers/choicewp', 'id' => $model->id, 'id_wp' => $id_wp]);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ];

        if(!isset($id_wp)) unset($cols[6]);
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $cols,
    ]); ?>

</div>
