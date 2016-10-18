<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Netints;

/* @var $this yii\web\View */
/* @var $model backend\models\Netints */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="netints-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mac')->textInput() ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipaddr')->textInput() ?>

    <?= $form->field($model, 'domain_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Netints::arrTypes()) ?>

    <?= $form->field($model, 'port_count')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
