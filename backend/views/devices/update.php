<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Devices */
/* @var $dt_mac boolean */
/* @var $dt_imei boolean */
/* @var $model->deviceType backend\models\DeviceType */

$this->title = 'Редактировать: ' . $model->id. ' ' . $model->deviceType->title . ' ' . $model->device_note;
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->deviceType->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id_wp' => $id_wp,
        'mode' => $mode,
        'dt_mac' => $dt_mac,
        'dt_imei' => $dt_imei,
    ]) ?>

</div>
