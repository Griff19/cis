<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подразделения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать подразделение', ['create'], ['class' => 'btn btn-success']) ?>
        <?php if(Yii::$app->user->can('admin')){
            echo Html::a('Заполнить из файла', ['readfile'], ['class' => 'btn btn-success']);
        }?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'branch_title',
            'lannet',
            'city_address',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
