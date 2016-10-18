<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Netints */

$this->title = 'Добавить интерфейс';
$this->params['breadcrumbs'][] = ['label' => 'Сетевые интерфейсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="netints-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
