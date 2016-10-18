<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Parameters */

$this->title = $dev_name . ' параметры:';
$this->params['breadcrumbs'][] = ['label' => 'Параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameters-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,

    ]) ?>

</div>
