<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */

$this->title = $model->cell_number;
$this->params['breadcrumbs'][] = ['label' => 'Мобильные номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cell-numbers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверенны что хотите удалить номер'. $this->title .'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cell_number',
            ['attribute' => 'employee_id',
                'header' => 'Владелец',
                'value' => $model->employee->snp,
            ],
            'status',
        ],
    ]) ?>

</div>
