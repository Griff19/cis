<?php

use backend\models\Employees;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\jui\DatePicker;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-enquiries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'employee_id')->textInput() ?>

    <?= $form->field($model, 'employee_name')->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => Employees::arraySnpId(),
            'minLength' => '1',
            'autoFill' => true,
            'select' => new JsExpression("function( event, ui ) {
                $('#employee_id_hidd').val(ui.item.id);
             }")],
        'options' => ['class' => 'form-control']
    ]) ?>
    <?= $form->field($model, 'employee_id')->hiddenInput(['id' => 'employee_id_hidd'])->label(false) ?>

    <?php echo $form->field($model, 'create_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'do_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'memo')->widget('\kartik\checkbox\CheckboxX', [
        'autoLabel' => true,
        'pluginOptions' => ['threeState' => false]
    ])->label(false)?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
