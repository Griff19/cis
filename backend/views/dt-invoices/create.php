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
	<p> <span class="glyphicon glyphicon-info-sign"></span>
		Заполните эту форму после того как получили счет от поставщика.
	</p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	<p> <span class="glyphicon glyphicon-info-sign"></span>
		Скан оригинального документа можно будет добавить на странице просмотра счета.
	</p>
</div>
