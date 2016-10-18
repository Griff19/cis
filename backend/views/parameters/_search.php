<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameters-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'brend') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'sn') ?>

    <?= $form->field($model, 'mac') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'nport') ?>

    <?php // echo $form->field($model, 'login') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'biospass') ?>

    <?php // echo $form->field($model, 'radmin') ?>

    <?php // echo $form->field($model, 'dns') ?>

    <?php // echo $form->field($model, 'voip') ?>

    <?php // echo $form->field($model, 'aster_pwd') ?>

    <?php // echo $form->field($model, 'aster_cont') ?>

    <?php // echo $form->field($model, 'imei') ?>

    <?php // echo $form->field($model, 'id_device') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
