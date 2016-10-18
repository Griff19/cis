<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StoryWorkplace */

$this->title = 'Update Story Workplace: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Story Workplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="story-workplace-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
