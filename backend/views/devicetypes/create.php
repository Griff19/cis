<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DeviceType */

$this->title = 'Добавить тип устройства';
$this->params['breadcrumbs'][] = ['label' => 'Типы устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
