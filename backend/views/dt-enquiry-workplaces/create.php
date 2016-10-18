<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiryWorkplaces */

$this->title = 'Create Dt Enquiry Workplaces';
$this->params['breadcrumbs'][] = ['label' => 'Dt Enquiry Workplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-enquiry-workplaces-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
