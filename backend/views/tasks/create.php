<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Tasks */

$this->title = 'Новое сообщение';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
