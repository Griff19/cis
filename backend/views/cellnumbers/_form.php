<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cell-numbers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cell_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employee_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
