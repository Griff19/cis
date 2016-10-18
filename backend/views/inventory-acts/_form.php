<?php

use backend\models\Employees;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-acts-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    if (!$model->workplace_id)
        echo $form->field($model, 'workplace_id')->textInput([
        'onchange' => "$.post('/admin/workplaces/get-owner-id?id='+$(this).val(), function(data){
            var model = $.parseJSON(data);
            $('#inventoryacts-owner_employee_id').val(model[0]);
            $('#inventoryacts-owner_name').val(model[1]);
        });"]);
    else
        echo '<p><b>Рабочее место № </b>'. $model->workplace_id .'</p>';
    ?>

    <?= $form->field($model, 'owner_name')->widget(
        AutoComplete::className(),[
        'clientOptions' => [
            'source' => Employees::arraySnpId(),
            'minLength'=>'3',
            'autoFill'=>true,
            'select' => new JsExpression("function( event, ui ) {
                    $('#inventoryacts-owner_employee_id').val(ui.item.id);
                 }")],
        'options' => ['class' => 'form-control']
    ])?>
    <?= $form->field($model, 'owner_employee_id')->textInput(['readonly'=>true]); //#inventoryacts-owner_employee_id ?>


    <?= $form->field($model, 'employee_name')->widget(
        AutoComplete::className(),[
            'clientOptions' => [
                'source' => Employees::arraySnpId(),
                'minLength'=>'3',
                'autoFill'=>true,
                'select' => new JsExpression("function( event, ui ) {
                    $('#inventoryacts-exec_employee_id').val(ui.item.id);
                 }")],
            'options' => ['class' => 'form-control']
        ])?>
    <?= $form->field($model, 'exec_employee_id')->hiddenInput()->label(false);  //#inventoryacts-exec_employee_id ?>

    <?= $form->field($model, 'act_date')->widget(DatePicker::className(), [
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?php //echo $form->field($model, 'curr_date')->textInput() ?>

    <?php //echo $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'comm')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
