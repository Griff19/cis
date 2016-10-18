<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DPartners */

$this->title = 'Создать Контрагента';
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpartners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
