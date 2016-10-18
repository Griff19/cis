<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Netints;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NetintsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сетевые интерфейсы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="netints-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'mac',
            'ipaddr',
            'domain_name',
            'port_count',
            'vendor',
            [
                'attribute' => 'type',
                'value' => 'type',
                'filter' => Netints::arrTypes()
                ],
            'devices.device_note',
            'deviceType.title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
