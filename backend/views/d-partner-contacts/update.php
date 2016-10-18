<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContacts */

$this->title = 'Изменить контакт ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['d-partners/index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner->brand, 'url' => ['d-partners/view', 'id' => $model->partner_id]];
$this->params['breadcrumbs'][] = ['label' => $model->full_name, 'url' => '#'];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="dpartner-contacts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
