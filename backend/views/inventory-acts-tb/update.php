<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActsTb */

$this->title = 'Update Inventory Acts Tb: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Acts Tbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inventory-acts-tb-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
