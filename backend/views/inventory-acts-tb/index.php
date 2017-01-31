<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InventoryActsTbSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDoc \backend\models\InventoryActs */

//$this->title = 'Inventory Acts Tbs';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-acts-tb-index">
    <h3> Перемещаемые на РМ устройства: </h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'act_id',
            'device_id',
            'device_workplace_id',
            'aux',
            'status',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'inventory-acts-tb',
                'template' => '{delete}'
            ],
        ],
    ]);    ?>

</div>
