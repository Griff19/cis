<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VoipNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="voip-numbers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'voip_number')->textInput() ?>

    <?= $form->field($model, 'secret')->textInput() ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'context')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
