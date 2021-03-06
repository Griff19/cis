<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContracts */

$this->title = 'Добавить Контракт';
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['d-partners/index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner->brand, 'url' => ['d-partners/view', 'id' => $model->partner_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpartner-contracts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
//        'partner' => $model->partner,
    ]) ?>

</div>
