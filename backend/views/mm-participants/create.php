<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MmParticipants */

$this->title = 'Create Mm Participants';
$this->params['breadcrumbs'][] = ['label' => 'Mm Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-participants-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
