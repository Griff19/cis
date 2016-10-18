<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheetDevices */

$this->title = 'Create Dt Defsheet Devices';
$this->params['breadcrumbs'][] = ['label' => 'Dt Defsheet Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-defsheet-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
