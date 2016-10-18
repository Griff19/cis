<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Tasks;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Сообщение', ['create'], ['class' => 'btn btn-success']) ?>
        <?php if(Yii::$app->user->can('admin'))
            echo Html::a('Все сообщения...', ['index', 'mode' => 1], ['class' => 'btn btn-default']); ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            $option = '';
            if ($model->status == Tasks::STATUS_CREATED)
                $option = ['class' => 'danger'];
            return $option;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'date_send',
            ['attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->userTo->username;
                }
            ],
            'subject',
//            ['attribute' => 'type',
//                'value' => function($model) {
//                    return $model->TypeStr();
//                }
//            ],
            'stringType',
            ['attribute' => 'content',
                'format' => 'raw'
            ],
            // 'status',
            // 'from_user_id',
            ['class' => '\yii\grid\Column',
                'content' => function($model){
                    if ($model->user_id == Yii::$app->user->id)
                        $str = '<span class="glyphicon glyphicon-log-in" title="Входящее"></span>';
                    if ($model->from_user_id == Yii::$app->user->id)
                        $str = '<span class="glyphicon glyphicon-log-out" title="Исходящее"></span>';
                    return $str;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => Yii::$app->user->can('admin') ? '{view} {delete}' : '{view}'
            ],
        ],
    ]); ?>

</div>
