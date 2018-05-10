<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpDevice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-device-form">

    <?php $form = ActiveForm::begin(); ?>

    <b><?= Html::label($model->attributeLabels()['tmp_workplace_id']. ' №')?> <?= $model->tmp_workplace_id ?></b>

    <div class="input-group">
        <span class="input-group-btn">
        <?= Html::a('Выбрать устройство...', ['devices/index', 'mode' => 'wps', 'target' => 'tmp-device/create', 'target_id' => $model->tmp_workplace_id], ['class' => 'btn btn-default'])?>
        </span>
        <?php echo $form->field($model, 'devices_id')->textInput()->label(false)->error(false) ?>
    </div>

    <div id="tmpdevice-devices_id_err" class="text-danger"></div>
    <br />
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    $('form').on('afterValidateAttribute', function(event, attr, msg) {
        $('#'+attr.id+'_err').html(msg[0]);
    });
JS;

$this->registerJs($script);
?>

