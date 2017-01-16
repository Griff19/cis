<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Message;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->status < Message::STATUS_READ)
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
            'stringType',
            'stringStatus',
            'date_send:datetime',
            ['attribute' => 'from_user_id',
                'value' => $model->userFrom ? $model->userFrom->username : null
            ],
            ['attribute' => 'Имя От кого:',
                'value' => $model->employeeFrom ? $model->employeeFrom->snp : null
            ],
            ['attribute' => 'user_id',
                'value' => $model->userTo->username,
            ],
            ['attribute' => 'Имя Кому:',
                'value' => $model->employeeTo->snp
            ],
            'subject',
            ['attribute' => 'content',
                'format' => 'raw'
            ],
            'date_complete',

        ],
    ]) ?>

</div>
