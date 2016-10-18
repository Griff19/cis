<?php

use backend\models\DeviceType;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
//use backend\models\Devices;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-enquiry-devices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'dt_enquiries_id')->textInput() ?>

    <?= $form->field($model, 'parent_device_id')->textInput([
       // 'onclick' => "javascript:document.location.href='/admin/devices/index?mode=dvs&target=dt-enquiry-devices/create1&id_doc=" . $model->dt_enquiries_id . "'"
    ]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(DeviceType::arrDevType(), 'type_id', 'title'), [
            'prompt' => 'Укажите тип устройства...'
        ]
    ) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
