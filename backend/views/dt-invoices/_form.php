<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use backend\models\DPartners;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-invoices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_date')->widget(DatePicker::className(), [
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'd_partners_name')->widget(
        AutoComplete::className(), [
            'clientOptions' => [
                'source' => DPartners::arrayPartners(),
                'minLength' => '1',
                'autoFill' => true,
                'select' => new JsExpression("function( event, ui ) {
                $('#partners_id_hidd').val(ui.item.id);
             }")],
            'options' => ['class' => 'form-control']
    ]) ?>

    <?= $form->field($model, 'd_partners_id')->hiddenInput(['id' => 'partners_id_hidd'])->label(false)?>

    <?= $form->field($model, 'delivery_type')->textInput() ?>

    <?= $form->field($model, 'summ')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
