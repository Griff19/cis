<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiries */

$this->title = 'Update Dt Enquiries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dt-enquiries-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
