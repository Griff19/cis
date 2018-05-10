<?php
/**
 * Таблица устройств, встраивается в страницу Виртуального рабочего места
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $twp \backend\models\TmpWorkplace */
/* @var $searchModel backend\models\TmpDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="tmp-device-index">
    <p>
        <?= Html::a('Create Tmp Device', ['tmp-device/create', 'id_twp' => $twp->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tmp_workplace_id',
            'devices_id',

            ['class' => 'yii\grid\ActionColumn', 'controller' => 'tmp-device'],
        ],
    ]); ?>
</div>
