<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Coordinate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coordinate-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'workplace_id')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'floor')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
		    <?= $form->field($model, 'branch_id')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'x')->hiddenInput()->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'y')->hiddenInput()->label(false) ?>
        </div>
    </div>
    <?= $form->field($model, 'balloon')->textInput(['maxlength' => true]) ?>

    В поле "<?= $model->attributeLabels()['preset'] ?>" записывается стока, определяющая вид метки. Для получения подробной информации по видам меток посетите страницу:
    <?= Html::a('Предустановленные опции',
        'https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage',
        ['target' => '_blank']) ?>.
    Для отображения стандартной метки оставьте поле пустым.
    <?= $form->field($model, 'preset')->textInput(['maxlength' => true]) ?>

    Поле "<?= $model->attributeLabels()['content']?>" содержит строку, которая будет выводится вместе с меткой (содержимое метки).
	<?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
