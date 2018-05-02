<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TmpWorkplace */
/* @var $dataTmpDevice \yii\debug\models\timeline\DataProvider */
/* @var $searchTmpDevice \backend\models\TmpDeviceSearch */

$this->title = "Виртуальное рабочее место №" . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Виртуальные рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-workplace-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить это место?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'workplace.summary',
        ],
    ]) ?>

    <?= $this->render('\..\tmp-device\index', ['dataProvider' => $dataTmpDevice, 'searchModel' => $searchTmpDevice]) ?>

</div>
