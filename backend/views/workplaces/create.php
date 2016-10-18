<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Workplaces */

$this->title = 'Создать рабочее место';
$this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplaces-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
