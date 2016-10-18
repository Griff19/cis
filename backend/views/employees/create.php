<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Employees */
if (isset($upload)) $this->title = 'Загрузка файла сотрудников...';
else $this->title = 'Создание сотрудника';

$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        if(isset($upload)) echo $this->render('_form_file', ['model' => $model]);
        else echo $this->render('_form', ['model' => $model]); ?>

</div>
