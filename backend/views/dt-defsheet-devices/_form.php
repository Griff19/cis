<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheetDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-defsheet-devices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'dt_defsheets_id')->textInput() ?>
    <h4> Акт списания №<?= $model->dt_defsheets_id ?></h4>
    <?php // $form->field($model, 'devices_id')->textInput() ?>
    <h4> ИД устройства: <?= $model->devices_id ?></h4>

    <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <div class="form-group pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <br><br>

    <?php ActiveForm::end(); ?>

</div>
