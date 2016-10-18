<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Tasks;

/* @var $this yii\web\View */
/* @var $model backend\models\Tasks */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->status < Tasks::STATUS_READ)
            echo Html::a('Прочитано', ['read', 'id' => $model->id],
                ['class' => 'btn btn-success', 'title' => 'Отметить как прочитанное']);
        else {
            echo Html::a('Не Прочитано', ['not-read', 'id' => $model->id],
                ['class' => 'btn btn-danger', 'title' => 'Отметить как не прочитанное']);
            if ($model->type == 1)
                echo ' ' . Html::a('Выполнил', '', ['class' => 'btn btn-success']);
            else
                echo ' ' . Html::a('Ответить', '', ['class' => 'btn btn-success']);
            echo Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger']);
        }
        ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'date_send',
            ['attribute' => 'user_id',
                'value' => $model->userTo->username,
            ],
            ['attribute' => 'Имя Кому:',
                'value' => $model->employeeTo->snp
            ],
            'subject',
            'stringType',
            ['attribute' => 'content',
                'format' => 'raw'
            ],
            'stringStatus',
            'date_complete',
            ['attribute' => 'from_user_id',
                'value' => $model->userFrom ? $model->userFrom->username : null
            ],
            ['attribute' => 'Имя От кого:',
                'value' => $model->employeeFrom ? $model->employeeFrom->snp : null
            ]
        ],
    ]) ?>

</div>
