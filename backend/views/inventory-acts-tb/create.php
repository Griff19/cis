<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActsTb */

$this->title = 'Create Inventory Acts Tb';
$this->params['breadcrumbs'][] = ['label' => 'Inventory Acts Tbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-acts-tb-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
