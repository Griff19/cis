<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoices */

$this->title = 'Создание документа Счет';
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-invoices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
