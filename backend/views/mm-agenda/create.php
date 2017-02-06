<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MmAgenda */

$this->title = 'Create Mm Agenda';
$this->params['breadcrumbs'][] = ['label' => 'Mm Agendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-agenda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
