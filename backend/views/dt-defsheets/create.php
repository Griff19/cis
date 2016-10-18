<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheets */

$this->title = 'Создание Акта списания';
$this->params['breadcrumbs'][] = ['label' => 'Акты списания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-defsheets-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p> Для создания документа выберите сотрудника "Заявителя" </p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
