<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheets */
//
//$this->title = 'Акт на списание №' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Акты на списание', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Изменить сотрудника';
?>
<div class="dt-defsheets-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
