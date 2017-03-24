<?php
use yii\widgets\ActiveForm;
use backend\models\DeviceType;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<div class="device-form-search">

<?php
    $form = ActiveForm::begin([
        'action' => ['devices/find-device'],
        'method' => 'get'
    ]);

    echo $form->field($model, 'dt_title')->dropDownList(
    ArrayHelper::map(DeviceType::find()->orderBy('title')->all(), 'title', 'title'),
    ['prompt' => 'Выберите тип устройства...']);

    echo $form->field($model, 'brand')->textInput(['maxlength' => true]);
    echo $form->field($model, 'model')->textInput(['maxlength' => true]);
    echo $form->field($model, 'sn')->textInput(['maxlength' => true]);
    echo $form->field($model, 'imei1')->textInput(['maxlength' => true]);
    echo $form->field($model, 'imei2')->textInput(['maxlength' => true]);
    echo $form->field($model, 'specification')->textInput(['maxlength' => true]);
    echo $form->field($model, 'device_note')->textInput(['maxlength' => true]);

    echo Html::submitButton('Найти', ['class' => 'btn btn-primary']);
    echo ' ';
    echo Html::a('Сбросить', ['devices/find-device'], ['class' => 'btn btn-default']);
    ActiveForm::end();
?>

</div>
