<?php

use backend\models\Employees;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\MmParticipants */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs(
	'$("document").ready(function(){
       	$("#mm_part_frm").on("pjax:end", function() {
            $.pjax.reload({container:"#mm_part_idx"});
        });
    });'
);
?>
<div class="mm-participants-form">
<?php \yii\widgets\Pjax::begin(['id' => 'mm_part_frm']); ?>

    <?php $form = ActiveForm::begin(['options' => [
		'data-pjax' => true,
		'class' => 'form-inline'
	]]); ?>

    <?php //echo $form->field($model, 'mm_id')->textInput() ?>
	<?= $form->field($model, 'employee_name')->widget(
		'yii\jui\AutoComplete', [
			'clientOptions' => [
				'source' => Employees::arraySnpId(),
				'minLength' => 2,
				'select' => new JsExpression("function ( event, ui ) {
					$('#employee_id_hidd').val( ui.item.id );
				}")
			],
			'options' => ['class' => 'form-control', 'size' => 40]
		]
) ?>
    <?= $form->field($model, 'employee_id')->hiddenInput(['id' => 'employee_id_hidd'])->label(false) ?>
<div class="form-group" style="vertical-align: top">
	<?= Html::submitButton($model->isNewRecord ? 'Добавить участника' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

    <?php ActiveForm::end(); ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
