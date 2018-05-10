<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpDevice */

$this->title = 'Update Tmp Device: ' . $model->tmp_workplace_id;
$this->params['breadcrumbs'][] = ['label' => 'Tmp Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tmp_workplace_id, 'url' => ['view', 'tmp_workplace_id' => $model->tmp_workplace_id, 'devices_id' => $model->devices_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tmp-device-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
