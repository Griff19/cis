<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\bootstrap\ActiveForm;
use backend\models\AdminEmployees;

/* @var $this yii\web\View */
/* @var $model backend\models\EmployeesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-search">
    <br>
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'action' => ['site/admin_workplace?tab=2'],
        'method' => 'get',
    ]);

    ?>

    <?= $form->field($model, 'fio', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => AdminEmployees::arrayFios()
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>
    <?= $form->field($model, 'cell_number', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => AdminEmployees::arrayCells()
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]);?>
    <?= $form->field($model, 'email', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => AdminEmployees::arrayEmails()
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]);?>

    <div style="float: right">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбрсить', ['site/admin_workplace', 'tab' => 2], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
