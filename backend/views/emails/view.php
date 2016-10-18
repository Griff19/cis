<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Emails */

$this->title = $model->email_address;
$this->params['breadcrumbs'][] = ['label' => 'Электронные адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emails-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверенны что хотите удалить адрес'. $model->email_address .'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email_address:email',
            'employee_id',
            'status',
        ],
    ]) ?>

</div>
