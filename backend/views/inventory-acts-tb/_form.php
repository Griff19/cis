<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActsTb */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-acts-tb-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'act_id')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'device_workplace_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
