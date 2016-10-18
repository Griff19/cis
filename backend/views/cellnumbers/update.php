<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */

$this->title = 'Редактировать номер: ' . ' ' . $model->cell_number;
$this->params['breadcrumbs'][] = ['label' => 'Мобильные номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cell_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="cell-numbers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
