<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpMoving */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-moving-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'device_id')->textInput() ?>
    <?= Html::label($model->getAttributeLabel('device_id'), '', ['style' => 'line-height: 2'])?>: <?= $model->device->summary ?> <br />
    <?php //echo $form->field($model, 'workplace_from')->textInput() ?>
    <?= Html::label($model->getAttributeLabel('workplace_from'))?>: <?= $model->workplaceFrom->summary ?> <br />
    
    <?php //echo $form->field($model, 'workplace_where')->textInput() ?>
    <?= Html::label($model->getAttributeLabel('workplace_where'))?>: <?= $model->workplaceWhere ? $model->workplaceWhere->summary : '' ?>
    <?= Html::a('Выбрать место назначения', [
            'workplaces/index',
            'mode' => 'sel',
            'target' => 'tmp-moving/create',
            'id_dev' => $model->device_id,
            'target_id' => $model->workplace_from,
        ], ['class' => 'btn btn-sm btn-default']) ?>
    <?php echo $form->field($model, 'workplace_where')->textInput(['style' => 'display: none'])->label(false) ?>
    
    <?php //echo $form->field($model, 'user_id')->textInput() ?>
    <?= Html::label($model->getAttributeLabel('user_id'))?>: <?= $model->user->employee->snp ?>
    <?php //echo $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
//$script = <<<JS
//    $('#tmpmoving-workplace_where').after(
//        $('#btn-wp_where')
//    );
//JS;
//$this->registerJs($script);
?>
