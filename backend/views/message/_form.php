<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\User;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
//    echo $form->field($model, 'user_id')->widget(
//        AutoComplete::className(), [
//        'clientOptions' => [
//            'source' => User::arrayUsers(),
//        ],
//        'options' => [
//            'class' => 'form-control'
//        ]
//    ]);
    ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username'),
            ['prompt' => 'Укажите пользователя']);
    ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'from_user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Отправить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
