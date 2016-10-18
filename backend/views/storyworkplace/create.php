<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StoryWorkplace */

$this->title = 'Create Story Workplace';
$this->params['breadcrumbs'][] = ['label' => 'Story Workplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-workplace-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
