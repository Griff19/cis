<?php

use backend\models\Rooms;
use backend\models\Branches;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model backend\models\Workplaces */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workplaces-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if (Yii::$app->user->can('auditor')) {?>

        <?= $form->field($model, 'branch_id')->dropDownList(
            ArrayHelper::map(Branches::getList(), 'id', 'branch_title'),
        ['prompt' => 'Выберите подраздление...',
        'onchange' => '$.post("/admin/rooms/list?id='.'"+$(this).val(), function(data) {
            $("select#workplaces-room_id").html(data);
        });'
        ]);
    ?>

    <?= //$form->field($model, 'room_id')->textInput()
        $form->field($model, 'room_id')->dropDownList(
        ArrayHelper::map(Rooms::find()->all(), 'id', 'room_title'),
        ['prompt'=>'Выберите отдел/кабинет...'])
    ?>
    <?php } else {
        echo '<p><b>Подразделение: </b>'. $model->branch->branch_title. '</p>';
        echo '<p><b>Кабинет: </b>'. $model->room->room_title. '</p>';
    }?>

    <?= $form->field($model, 'workplaces_title')->textInput(['maxlength' => true])?>
    <?= $form->field($model, 'mu')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
