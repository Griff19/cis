<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContactsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpartner-contacts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'full_name') ?>

    <?= $form->field($model, 'job_title') ?>

    <?= $form->field($model, 'phone1') ?>

    <?= $form->field($model, 'phone2') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'partner_id') ?>

    <?php // echo $form->field($model, 'title') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
