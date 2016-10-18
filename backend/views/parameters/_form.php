<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Parameters */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameters-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brend')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mac')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nport')->textInput() ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'biospass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'radmin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dns')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'voip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aster_pwd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aster_cont')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imei')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
