<?php
use backend\models\Workplaces;
use backend\models\Employees;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WpOwners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-owners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'workplace_id')->dropDownList(
        ArrayHelper::map(Workplaces::find()->all(), 'id', 'workplaces_title'),
        ['prompt' => 'Выберите рабочее место...'])
    ?>

    <?= $form->field($model, 'employee_id')->dropDownList(
        ArrayHelper::map(Employees::find()->all(), 'id', function($model) {return $model->surname . ' ' . $model->name;}),
        ['prompt' => 'Выберите сотрудника...'])
    ?>

    <?= $form->field($model, 'event')->checkbox() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
