<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContracts */

$this->title = 'Изменить Контракт №' . $model->contract_number . ' от ' . $model->contract_date;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['d-partners/index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner->brand, 'url' => ['d-partners/view', 'id' => $model->partner_id]];
$this->params['breadcrumbs'][] = 'Контракт №' . $model->contract_number . ' от ' . $model->contract_date;
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="dpartner-contracts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
