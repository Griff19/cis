<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtDefsheetsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акты списания';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-defsheets-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать акт списания', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_create',
            'date_confirm',
            'status',
            'employee_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
