<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Emails */

$this->title = 'Редактировать адрес: ' . ' ' . $model->email_address;
$this->params['breadcrumbs'][] = ['label' => 'Электронные адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email_address, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="emails-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
