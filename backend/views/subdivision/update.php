<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Subdivision */

$this->title = 'Update Subdivision: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Subdivisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subdivision-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
