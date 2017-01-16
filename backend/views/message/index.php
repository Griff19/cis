<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Message;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Сообщение', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Yii::$app->user->can('admin') ? Html::a('Все сообщения...', ['index', 'mode' => 1], ['class' => 'btn btn-default'])
											: ''; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            $option = '';
            if ($model->status == Message::STATUS_CREATED)
                $option = ['class' => 'danger'];
            return $option;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'stringType',
            'date_send:datetime',
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

            ['attribute' => 'content',
                'format' => 'raw'
            ],
            // 'status',
            // 'from_user_id',
            ['class' => '\yii\grid\Column',
                'content' => function($model){
                    $str = '';
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
