<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MmDecision */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs(
	'$("document").ready(function(){
       	$("#mmdecision_frm").on("pjax:end", function() {
            $.pjax.reload({container:"#mmdecision_idx"});
        });
    });'
);
?>
<div class="mmdecision-form">
<?php \yii\widgets\Pjax::begin(['id' => 'mmdecision_frm']); ?>
    <?php $form = ActiveForm::begin(['options' => [
		'id' => 'form-mmdecision',

		'data-pjax' => true
	]]); ?>

    <?php //echo $form->field($model, 'mm_id')->textInput() ?>

    <?= $form->field($model, 'content')->textarea([
		'rows' => 3,
        'style' => 'resize: none',
		'placeholder' => 'Введите принятое решение и дату исполнения...'
	])->label(false) ?>

	<?php
		echo Html::activeLabel($model, 'due_date') . ' ';
		echo $form->field($model, 'due_date', ['options' => ['style' => 'display: inline-block']])->widget('\yii\jui\DatePicker', [
			'options' => ['class' => 'form-control']])->label(false) . ' ';
		echo Html::submitButton($model->isNewRecord ? 'Добавить решение' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
	?>

    <?php ActiveForm::end(); ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
