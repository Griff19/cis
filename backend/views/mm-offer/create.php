<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MmOffer */

$this->title = 'Create Mm Offer';
$this->params['breadcrumbs'][] = ['label' => 'Mm Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-offer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
