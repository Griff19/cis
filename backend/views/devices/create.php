<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Devices */

$this->title = 'Создать устройство';
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id_wp' => $id_wp,
        'id_dev' => $id_dev,
        'mode' => $mode
    ]) ?>

</div>
