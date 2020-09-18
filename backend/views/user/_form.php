<?php

use backend\models\Employees;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/* @var $this yii\web\View
 * @var $model backend\models\User
 * @var $form yii\widgets\ActiveForm
 * @var $admin boolean
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin();
        echo $form->field($model, 'username')->textInput(['maxlength' => 255]);
        echo $form->field($model, 'employee_name')->widget(
                AutoComplete::class,[
                'clientOptions' => [
                    'source' => Employees::arraySnpIdMail(),
                    'minLength'=>'3',
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                    $('#user-employee_id').val(ui.item.id);
                    $('#user-email').val(ui.item.email);
                 }")],
                'options' => ['class' => 'form-control']
            ]);

        echo $form->field($model, 'employee_id')->textInput(['readonly' => true]);
        echo $form->field($model, 'email')->textInput(['maxlength' => 255]);

//        if ($admin){
//            //echo $form->field($model, '_1c_id')->textInput(['maxlength' => 36]);
//            //echo $form->field($model, 'fullname')->textInput(['maxlength' => 255]);
//            echo $form->field($model, 'newpass')->passwordInput();
//            echo $form->field($model, 'confnewpass')->passwordInput();
//        }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <p><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Изменить пароль можно по кнопке "Изменить пароль" на странице пользователя</p>
</div>
