<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Отделы/Кабинеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'room_title',
            //'branch_id',
            'branch.branch_title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
