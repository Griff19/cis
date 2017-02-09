<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MmOffer */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs(
	'$("document").ready(function(){
       	$("#mmoffer_frm").on("pjax:end", function() {
            $.pjax.reload({container:"#mmoffer_idx"});
        });
    });'
);
?>
<div class="mmoffer-form">
	<?php \yii\widgets\Pjax::begin(['id' => 'mmoffer_frm']); ?>
		<?php $form = ActiveForm::begin(['options' => [
			'id' => 'form-offer',
			'data-pjax' => true
		]]); ?>

		<?php //echo $form->field($model, 'mm_id')->textInput() ?>

		<?= $form->field($model, 'content')->textarea([
			'rows' => 3,
			'placeholder' => 'Введите новое предложение...',
			'onkeydown' => 'check_keys(event, this.form, "mmoffer", ' .$model->mm_id.');'
		])->label(false); ?>

		<div class="form-group" style="float: right">
			<?= Html::submitButton($model->isNewRecord ? 'Добавить предложение Ctrl+Enter' : 'Update', [
				'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
			]) ?>
		</div>

		<?php ActiveForm::end(); ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>
