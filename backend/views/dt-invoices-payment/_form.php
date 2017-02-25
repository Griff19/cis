<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\jui\AutoComplete;
use backend\models\Employees;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoicesPayment */
/* @var $form yii\widgets\ActiveForm */
/* @var $append string Определяет открывать ли виджет в модальном окне */
?>

<div class="dt-invoices-payment-form" id = 'pay_form'>

    <?php $form = ActiveForm::begin(['options' => ['id' => 'form_pay']]); ?>
    <h4>Документ № <?= $model->dt_invoices_id ?></h4>
    <?php //echo $form->field($model, 'dt_invoices_id')->textInput() ?>

    <?=
    $form->field($model, 'agreed_date')->widget(DatePicker::className(), [
        'dateFormat' => 'dd.MM.y',
        'options' => ['class' => 'form-control']
    ])
    ?>

    <?= $form->field($model, 'summ')->textInput() ?>
    <?php
        $clientOptions = [
            'source' => Employees::arraySnpId(),
            'minLength' => 3,
            'appendTo' => '#pay_form',
            'select' => new JsExpression("function ( event, ui ){
                    $('#employee_id_hidd').val( ui.item.id );
                }")
        ];

    ?>
    <?= $form->field($model, 'employee_name')->widget(
        AutoComplete::className(), [
            'clientOptions' => $clientOptions,
            'options' => ['class' => 'form-control']
        ]
    ) ?>
    <?= $form->field($model, 'employee_id')->hiddenInput(['id' => 'employee_id_hidd'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
