<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Devices */

$this->title = 'Редактировать: ' . $model->id. ' ' . $model->device_note;
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->device_note, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id_wp' => $id_wp,
        'mode' => $mode
    ]) ?>

</div>
