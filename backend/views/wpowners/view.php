<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WpOwners */

$this->title = $model->workplace_id;
$this->params['breadcrumbs'][] = ['label' => 'Wp Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-owners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'workplace_id' => $model->workplace_id, 'employee_id' => $model->employee_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'workplace_id' => $model->workplace_id, 'employee_id' => $model->employee_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'workplace_id',
            'employee_id',
            'event:boolean',
            'date',
        ],
    ]) ?>

</div>
