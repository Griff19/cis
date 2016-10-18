<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Workplaces */

$this->title = $model->workplaces_title;
$this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workplaces_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="workplaces-update">
    <h4>Редактировать:</h4>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
