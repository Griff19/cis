<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MeetingMinutes */

$this->title = 'Создать новый протокол';
$this->params['breadcrumbs'][] = ['label' => 'Протоколы встреч', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-minutes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
