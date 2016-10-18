<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Netints */

$this->title = 'Редактировать интерфейс: ' . $model->ipaddr . ' ' . $model->mac;
$this->params['breadcrumbs'][] = ['label' => 'Сетевые интерфейсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ipaddr . ' ' . $model->mac, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="netints-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
