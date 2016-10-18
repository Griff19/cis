<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheetDevices */

//$this->title = 'Update Dt Defsheet Devices: ' . $model->dt_defsheets_id;
//$this->params['breadcrumbs'][] = ['label' => 'Dt Defsheet Devices', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->dt_defsheets_id, 'url' => ['view', 'dt_defsheets_id' => $model->dt_defsheets_id, 'devices_id' => $model->devices_id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dt-defsheet-devices-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
