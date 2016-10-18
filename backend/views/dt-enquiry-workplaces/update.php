<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryWorkplaces */

$this->title = 'Update Dt Enquiry Workplaces: ' . $model->dt_enquiries_id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiry Workplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dt_enquiries_id, 'url' => ['view', 'dt_enquiries_id' => $model->dt_enquiries_id, 'workplace_id' => $model->workplace_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dt-enquiry-workplaces-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
