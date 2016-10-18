<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\DeviceType;

/* @var $this yii\web\View */
/* @var $model backend\models\DevicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(DeviceType::find()->all(), 'title', 'title'), ['prompt' => 'Выберите тип устройства...']
    ) ?>

    <?= $form->field($model, 'device_note') ?>

    <?php // echo $form->field($model, 'workplace_id') ?>

    <?= $form->field($model, 'brand') ?>

    <?php // echo $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'sn') ?>

    <?php // echo $form->field($model, 'specification') ?>

    <?php // echo $form->field($model, 'imei1') ?>

    <?php // echo $form->field($model, 'imei2') ?>

    <?php // echo $form->field($model, 'parent_device_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск!', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сброс...', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
