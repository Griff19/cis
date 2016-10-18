<?php

use backend\models\Workplaces;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StoryworkplaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$wp = Workplaces::findOne(['id' => $id_wp]);
$this->title = 'История рабочего места "' . $wp->workplaces_title . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-workplace-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'id_wp',
            //'id_employee',
            'employee.snp',
            'date_up',
            'event',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
