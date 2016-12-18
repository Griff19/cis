<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Devices */
/* @var $dt_mac boolean */
/* @var $dt_imei boolean */

$this->title = 'Создать устройство';
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id_wp' => $id_wp,
        'id_dev' => $id_dev,
        'mode' => $mode,
        'dt_mac' => $dt_mac,
        'dt_imei' => $dt_imei,

    ]) ?>

</div>
