<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Parameters */

$this->title = 'Редактировать параметры: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="parameters-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
