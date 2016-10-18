<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cell-numbers-form">

    <p>Загружаемый файл должен иметь три поля, разделенных символом табуляции:</p>
    <p>Абонент.Код	Абонент	Абонентский номер<br>
        0547	Авдонина Надежда Ивановна	923-505-50-06<br>
        1454	Азаркина Александра Сергеевна	923-416-07-40<br>
        ...
    </p>
    <p>Если в строке встречается слово "Абонент" то она игнорируется.</p>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
