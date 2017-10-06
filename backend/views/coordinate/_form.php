<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Coordinate */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
/*
    .field-coordinate-floor, .field-coordinate-x, .field-coordinate-y, .field-coordinate-workplace_id {
    display: inline-flex;
}

.field-coordinate-floor label, .field-coordinate-x label, .field-coordinate-y label, .field-coordinate-workplace_id label {
    margin-right: 10px;
    padding-top: 7px;
    width: 100%;
    text-align: right;
}

.field-coordinate-floor input, .field-coordinate-x input, .field-coordinate-y input, .field-coordinate-workplace_id input {
    margin-right: 10px;
}
*/
</style>

<div class="coordinate-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
    <?= $form->field($model, 'workplace_id')->textInput() ?>
        </div>
        <div class="col-md-3">
    <?= $form->field($model, 'floor')->textInput() ?>
        </div>
        <div class="col-md-3">
    <?= $form->field($model, 'x')->textInput() ?>
        </div>
        <div class="col-md-3">
    <?= $form->field($model, 'y')->textInput() ?>
        </div>
    </div>
    <?= $form->field($model, 'balloon')->textInput(['maxlength' => true]) ?>

    Для получения подробной информации по видам меток посетите страницу:
    <?= Html::a('Предустановленные опции',
        'https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage',
        ['target' => '_blank']) ?>
    <?= $form->field($model, 'preset')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
