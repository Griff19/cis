<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TmpWorkplace */

$this->title = 'Создание Виртуального рабочего места';
$this->params['breadcrumbs'][] = ['label' => 'Виртуальные рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-workplace-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
