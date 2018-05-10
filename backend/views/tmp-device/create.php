<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TmpDevice */

$this->title = 'Добавление устройства';
$this->params['breadcrumbs'][] = ['label' => 'Виртуальное РМ № '. $model->tmp_workplace_id, 'url' => ['tmp-workplace/view', 'id' => $model->tmp_workplace_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
