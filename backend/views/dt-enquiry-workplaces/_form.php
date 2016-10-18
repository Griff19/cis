<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryWorkplaces */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-enquiry-workplaces-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dt_enquiries_id')->textInput() ?>

    <?= $form->field($model, 'workplace_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
