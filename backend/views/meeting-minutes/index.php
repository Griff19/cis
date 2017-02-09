<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeetingMinutesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Протоколы встреч';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-minutes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать новый протокол', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'doc_num',
            'doc_date:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
