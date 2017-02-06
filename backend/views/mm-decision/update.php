<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MmDecision */

$this->title = 'Update Mm Decision: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mm Decisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mm-decision-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
