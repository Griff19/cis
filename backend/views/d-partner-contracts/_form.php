<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContracts */
/* @var $partner \backend\models\DPartners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpartner-contracts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contract_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?php //echo $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
