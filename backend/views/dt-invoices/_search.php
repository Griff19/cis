<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-invoices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doc_number') ?>

    <?= $form->field($model, 'doc_date') ?>

    <?= $form->field($model, 'd_partners_id') ?>

    <?= $form->field($model, 'delivery_type') ?>

    <?php // echo $form->field($model, 'summ') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
