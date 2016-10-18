<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\DeviceType;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoiceDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-invoice-devices-form">

    <?php $form = ActiveForm::begin(); ?>
    <h4> Счет id:<?= $model->dt_invoices_id ?></h4>
    <h4> Заявка id:<?= $model->dt_enquiries_id ?></h4>
    <?php //echo $form->field($model, 'dt_invoices_id')->textInput() ?>
    <h4> Тип устройства: <?= DeviceType::getTitle($model->type_id) ?></h4>
    <?php //echo $form->field($model, 'type_id')->dropDownList(
        //ArrayHelper::map(DeviceType::arrDevType(), 'type_id', 'title'),
        //['prompt' => 'Выберите тип устройства...'])
    ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
