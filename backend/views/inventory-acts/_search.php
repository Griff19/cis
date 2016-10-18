<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-acts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'workplace_id') ?>

    <?= $form->field($model, 'owner_employee_id') ?>

    <?= $form->field($model, 'exec_employee_id') ?>

    <?= $form->field($model, 'act_date') ?>

    <?php // echo $form->field($model, 'curr_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'comm') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
