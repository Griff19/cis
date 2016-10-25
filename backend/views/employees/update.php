<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Employees */

$this->title = 'Редактировать сотрудника: ' .  $model->snp;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->snp, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="employees-update">
    <p>
        ФИО в загружаемом файле хранится одной строкой. <br>
        Если в этой форме оставить поля "Фамилия", "Имя", "Отчество" пустыми, то они обновятся автоматически из строки ФИО<br>
        Для изменения ФИО нужно изменить поля "Фамилия", "Имя" и "Отчество"<br>
    </p>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
