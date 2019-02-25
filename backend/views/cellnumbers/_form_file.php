<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cell-numbers-form">

    <p>Форма предназначена для загрузки:<br>
        1. Файла с данными о привязке номеров телефонов (на данный момент не используется)<br>
        2. Файла с месячными затратами на связь</p>
    <p>1. Загружаемый файл должен иметь три поля, разделенных символом табуляции:</p>
    <p>Абонент.Код	Абонент	Абонентский номер<br>
        0547	Авдонина Надежда Ивановна	923-505-50-06<br>
        1454	Азаркина Александра Сергеевна	923-416-07-40<br>
        ...
    </p>
    
    <p>2. Полученный от оператора файл .xls перед загрузкой должен быть сконвертирован в формат .xml</p>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
