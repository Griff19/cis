<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpOwnersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wp Owners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-owners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Wp Owners', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'workplace_id',
            'employee_id',
            'event:boolean',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
