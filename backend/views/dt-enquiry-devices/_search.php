<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiriyDevicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-enquiry-devices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dt_enquiries_id') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'parent_device_id') ?>

    <?= $form->field($model, 'note') ?>

    <?= $form->field($model, 'id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
