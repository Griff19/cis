<?php

use yii\helpers\Html;
use backend\assets\MapAsset;
use backend\models\Workplaces;
use backend\models\Coordinate;

MapAsset::register($this);

/**
 * @var $this yii\web\View
 * @var $model backend\models\Coordinate
 * @var $mod integer
 */

$this->title = 'Добавить расположение';
$this->params['breadcrumbs'][] = ['label' => 'Координаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coordinate-create">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= Html::a('Этаж 1', ['create', 'floor' => 1, 'branch' => $model->branch_id, 'id_wp' => $model->workplace_id, 'mod' => $mod],
        ['class' => 'btn btn-default', 'style' => $model->floor == 1 ? 'font-weight: 600' : ''])?>
	<?= Html::a('Этаж 2', ['create', 'floor' => 2, 'branch' => $model->branch_id, 'id_wp' => $model->workplace_id, 'mod' => $mod],
        ['class' => 'btn btn-default', 'style' => $model->floor == 2 ? 'font-weight: 600' : ''])?>
    <div id="map" class="map"></div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<script>
    let floor = <?= $model->floor ?>;
    let branch = <?= $model->branch_id ?>;
    let edit = true;
    let points = [];
    let max_zoom = <?= Coordinate::getMapParams($model->branch_id)['max_zoom'] ?>;
    let pic_width = <?= Coordinate::getMapParams($model->branch_id)['pic_width'] ?>;
    let pic_height = <?= Coordinate::getMapParams($model->branch_id)['pic_height'] ?>;

    <?php
    $owners = [];
    foreach ( $allCoord->models as $coordinate ) {
        if ($coordinate->workplace_id == $model->workplace_id) {continue;}
        $workplace = Workplaces::findOne($coordinate->workplace_id);
        $title = '';
        if ($workplace->owner) {
            if ($workplace->owner[0]){
                $title = $workplace->owner[0]->snp . '<br>' . $workplace->owner[0]->job_title;
                $owners[$workplace->owner[0]->snp] = $workplace->owner[0]->snp;
            }
        }
        if (Yii::$app->user->can('it')) {
            $balloon = Html::a('<b>№' . $workplace->id . '</b> ' . $workplace->workplaces_title, ['/admin/workplaces/view', 'id' => $workplace->id]) . '<br>' . $title;
        } else {
            $balloon = '<b>№' . $workplace->id . '</b> ' . $workplace->workplaces_title . '<br>' . $title;
        }
    ?>
    points.push({y: <?= $coordinate->y ?>, x: <?= $coordinate->x ?>, balloonContent: '<?= $balloon ?>', preset: 'islands#grayDotIcon'});
    <?php } ?>

</script>
