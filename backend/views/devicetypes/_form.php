<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DeviceType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'synonyms')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'comp')->dropDownList(['Нет', 'Да']) ?>
    <?= $form->field($model, 'mac')->dropDownList(['Нет', 'Да']) ?>
    <?= $form->field($model, 'imei')->dropDownList(['Нет', 'Да']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
