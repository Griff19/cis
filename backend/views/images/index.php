<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Изображения';
$this->params['breadcrumbs'][] = $this->title;

$param = Yii::$app->request->queryParams;
$target = ArrayHelper::getValue($param, 'target');
if($param) $query = http_build_query($param); else $query = '';
?>
<div class="images-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новое изображение', ['create?' . $query], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        ['class' => \yii\grid\Column::className(),
            'header' => 'Изображение',
            'content' => function($model) {
                return Html::img('/admin/'.$model->linkfile, ['style' => 'width:200px']);
            }
        ],
        'linkfile',
        'owner',
        'title',
        ['class' => \yii\grid\Column::className(),
            'content' => function($model) {
                return Html::a('выбрать', ['images/setowner',
                    'id' => $model->id,
                    'param' => Yii::$app->request->queryString]);
            }
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ];
    if (!isset($owner))
        unset($columns[6]);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,

    ]); ?>
</div>
