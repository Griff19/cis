<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MeetingMinutes */

$this->title = 'Create Meeting Minutes';
$this->params['breadcrumbs'][] = ['label' => 'Meeting Minutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-minutes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
