<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StoryDevice */

$this->title = 'Create Story Device';
$this->params['breadcrumbs'][] = ['label' => 'Story Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
