<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryDevices */

$this->title = 'Update Dt Enquiry Devices: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiry Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dt-enquiry-devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
