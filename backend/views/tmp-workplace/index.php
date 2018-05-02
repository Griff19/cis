<?php

use backend\models\TmpWorkplace;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TmpWorkplaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Виртуальные рабочие места';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-workplace-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Рабочее место', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute' => 'workplaces_id',
                'value' => function(TmpWorkplace $model){
                    return $model->workplace->getSummary();
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
