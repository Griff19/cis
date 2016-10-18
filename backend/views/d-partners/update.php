<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartners */

$this->title = 'Редактировать Контрагента ' . $model->name_partner;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_partner, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="dpartners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
