<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MmDecision */

$this->title = 'Create Mm Decision';
$this->params['breadcrumbs'][] = ['label' => 'Mm Decisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-decision-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
