<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpOwners */

$this->title = 'Update Wp Owners: ' . ' ' . $model->workplace_id;
$this->params['breadcrumbs'][] = ['label' => 'Wp Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workplace_id, 'url' => ['view', 'workplace_id' => $model->workplace_id, 'employee_id' => $model->employee_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wp-owners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
