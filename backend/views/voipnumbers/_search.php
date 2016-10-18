<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VoipnumbersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="voip-numbers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'voip_number') ?>

    <?= $form->field($model, 'secret') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'context') ?>

    <?php // echo $form->field($model, 'device_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
