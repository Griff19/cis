<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use backend\models\AdminEmployees;

/* @var $this yii\web\View */
/* @var $model backend\models\Emails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="emails-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email_address')->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => AdminEmployees::arrayEmails()
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]);?>

    <?= $form->field($model, 'employee_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
