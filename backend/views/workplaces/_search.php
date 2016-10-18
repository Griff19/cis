<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use backend\models\AdminWorkplaces;


/* @var $this yii\web\View */
/* @var $model backend\models\WorkplacesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workplaces-search">
    <br>
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'action' => ['site/admin_workplace?tab=1'],
        'method' => 'get',
    ]);
    ?>

    <?= $form->field($model, 'voip_number', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
            'clientOptions' => [
                'source' => AdminWorkplaces::arrayVoips()
            ],
            'options' => [
                'class' => 'form-control'
            ]
        ]); ?>
    <?= $form->field($model, 'mac', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
            'clientOptions' => [
                'source' => AdminWorkplaces::arrayMacs()
            ],
            'options' => [
                'class' => 'form-control'
            ]
        ]); ?>
    <?= $form->field($model, 'domain_name', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
            'clientOptions' => [
                'source' => AdminWorkplaces::arrayDomains()
            ],
            'options' => [
                'class' => 'form-control'
            ]
        ]); ?>
    <?= $form->field($model, 'ip', ['horizontalCssClasses' => [
        'wrapper' => 'col-xs-9',
    ]])->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source' => AdminWorkplaces::arrayIps()
        ],
        'options' => [
            'class' => 'form-control'
        ]
    ]); ?>



    <div style="float: right">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбрсить', ['site/admin_workplace'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
