<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DtEnquiries */

$this->title = 'Создание заявки на оборудование';
$this->params['breadcrumbs'][] = ['label' => 'Заявки на оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-enquiries-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Заполните, пожалуйста, поля:</p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
