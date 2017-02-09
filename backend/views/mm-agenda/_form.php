<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MmAgenda */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs(
	'$("document").ready(function(){
       	$("#mmagenda_frm").on("pjax:end", function() {
            $.pjax.reload({container:"#mmagenda_idx"});
        });
    });'
);
?>
<div class="mmagenda-form">
	<?php \yii\widgets\Pjax::begin(['id' => 'mmagenda_frm']); ?>
		<?php $form = ActiveForm::begin(['options' => [
			'id' => 'form_agenda',
			'data-pjax' => true
		]]); ?>

		<?php //echo $form->field($model, 'mm_id')->textInput() ?>

		<div class="input-group">
			<?= $form->field($model, 'content')->textarea([
				'rows' => 3,
				'placeholder' => 'Введите новый пункт повестки...',
				'style' => 'resize: none',
				'onkeydown' => 'check_keys(event, this.form, "mmagenda", ' .$model->mm_id. ');'

			])->label(false) ?>
			<span class="input-group-btn">
			<?= Html::submitButton($model->isNewRecord ? 'Добавить повестку Ctrl+Enter' : 'Update', [
					'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
					'style' => 'padding: 20px 20px; top: 5px;'
			]) ?>
			</span>
		</div>

		<?php ActiveForm::end(); ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>


