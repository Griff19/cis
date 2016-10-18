<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpartners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_partner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_partner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
