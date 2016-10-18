<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryDevices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiry Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-enquiry-devices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'type_id',
            'parent_device_id',
            'note',
            'id',
        ],
    ]) ?>

</div>
