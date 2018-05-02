<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpWorkplace */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-workplace-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'workplaces_id')->textInput(['placeholder' => 'Связанное рабочее место не выбрано...']) ?>

    <?= Html::a('Выбрать РМ', ['/workplaces', 'mode' => 'sel', 'target' => 'tmp-workplace/create', 'target_id' => ''], ['class' => 'btn btn-default']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
