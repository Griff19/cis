<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WpOwners */

$this->title = 'Create Wp Owners';
$this->params['breadcrumbs'][] = ['label' => 'Wp Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-owners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
