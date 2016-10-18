<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use backend\models\Employees;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-defsheets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'date_create')->textInput() ?>
    <?php //echo $form->field($model, 'date_confirm')->textInput() ?>
    <?php //echo $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'employee_name')->widget(
        AutoComplete::className(),[
            'clientOptions' => [
                'source' => Employees::arraySnpId(),
                'appendTo' => '#modal',
                'minLength' => '1',
                'autoFill' => true,
                'select' => new JsExpression("function( event, ui ) {
                    $('#employee_id_hidd').val(ui.item.id);
                 }")],
            'options' => ['class' => 'form-control']
        ]);
    ?>
    <?= $form->field($model, 'employee_id')->hiddenInput(['id' => 'employee_id_hidd'])->label(false) ?>

    <div class="form-group pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <br><br>

    <?php ActiveForm::end(); ?>

</div>
