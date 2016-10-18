<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StoryWorkplace */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="story-workplace-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_wp')->textInput() ?>

    <?= $form->field($model, 'id_employee')->textInput() ?>

    <?= $form->field($model, 'date_up')->textInput() ?>

    <?= $form->field($model, 'event')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
