<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TmpMoving */

$this->title = 'Update Tmp Moving: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tmp Movings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tmp-moving-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
