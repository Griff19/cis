<?php

use yii\helpers\Html;
use backend\assets\MapAsset;

MapAsset::register($this);


/* @var $this yii\web\View */
/* @var $model backend\models\Coordinate */

$this->title = 'Create Coordinate';
$this->params['breadcrumbs'][] = ['label' => 'Coordinates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coordinate-create">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= Html::a('Этаж 1', ['create', 'floor' => 1, 'id_wp' => $model->workplace_id],
        ['class' => 'btn btn-default', 'style' => $model->floor == 1 ? 'font-weight: 600' : ''])?>
	<?= Html::a('Этаж 2', ['create', 'floor' => 2, 'id_wp' => $model->workplace_id],
        ['class' => 'btn btn-default', 'style' => $model->floor == 2 ? 'font-weight: 600' : ''])?>
    <div id="map" class="map"></div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<script>
    var floor = <?= $model->floor ?>;
    var edit = true;
</script>
