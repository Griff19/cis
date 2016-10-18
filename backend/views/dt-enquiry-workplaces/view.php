<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryWorkplaces */

$this->title = $model->dt_enquiries_id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiry Workplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-enquiry-workplaces-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'dt_enquiries_id' => $model->dt_enquiries_id, 'workplace_id' => $model->workplace_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'dt_enquiries_id' => $model->dt_enquiries_id, 'workplace_id' => $model->workplace_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dt_enquiries_id',
            'workplace_id',
        ],
    ]) ?>

</div>
