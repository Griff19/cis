<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Employees */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-form">
    <p>Необходимый формат загружаемого файла:</p>
    <p>ФИО;Код;Регион;Должность;Примечание;Код контрагента<br>
        Абдулин Роман Васильевич;0670;;Оператор по фасовке молочной продукции;;В8669<br>
        Абдулина Вера Федоровна;0347;;Рабочая(ий);;В7846<br>
        ...
    </p>
    <p>Если в строке встречается слово "Наименование" то она игнорируется.</p>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
