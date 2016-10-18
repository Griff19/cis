<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\NetintsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="netints-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mac') ?>

    <?= $form->field($model, 'vendor') ?>

    <?= $form->field($model, 'ipaddr') ?>

    <?= $form->field($model, 'domain_name') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'port_count') ?>

    <?php // echo $form->field($model, 'device_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
