<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Branches */

$this->title = 'Редактировать подразделение: ' . ' ' . $model->branch_title;
$this->params['breadcrumbs'][] = ['label' => 'Подразделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->branch_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="branches-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
