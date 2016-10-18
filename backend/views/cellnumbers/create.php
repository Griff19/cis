<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CellNumbers */

$this->title = 'Новый номер';
$this->params['breadcrumbs'][] = ['label' => 'Мобильные номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cell-numbers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if(isset($upload)) echo $this->render('_form_file', ['model' => $model]);
    else echo $this->render('_form', ['model' => $model]); ?>

</div>
