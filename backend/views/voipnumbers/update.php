<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VoipNumbers */

$this->title = 'Редактировать номер: ' . ' ' . $model->voip_number;
$this->params['breadcrumbs'][] = ['label' => 'Внутренние номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->voip_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="voip-numbers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
