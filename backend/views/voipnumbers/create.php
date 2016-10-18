<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\VoipNumbers */

$this->title = 'Создание нового номера';
$this->params['breadcrumbs'][] = ['label' => 'Внутренние номера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voip-numbers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if(isset($upload)) echo $this->render('_form_file', ['model' => $model]);
    else echo $this->render('_form', ['model' => $model]); ?>

</div>
