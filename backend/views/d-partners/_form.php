<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpartners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_partner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_partner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'legal_address')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'mailing_address')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'bik')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'check_account')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'corr_account')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
