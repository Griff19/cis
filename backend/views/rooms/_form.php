<?php

use backend\models\Branches;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rooms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_id')->dropDownList(
            ArrayHelper::map(Branches::find()->all(), 'id', 'branch_title'),
            ['prompt' => 'Выберите подразделение...'])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
