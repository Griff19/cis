<?php
/**
 * @var $devProvider \yii\data\ActiveDataProvider
 * @var $newDevProvider \yii\data\ActiveDataProvider
 * @var $lostDevProvider \yii\data\ActiveDataProvider
 * @var $consModelProvider \yii\data\ActiveDataProvider
 * @var $model InventoryActs
 * @var $oldModel InventoryActs
 * @var $oldModelTable \backend\models\InventoryActsTb
 */

use yii\grid\GridView;
//use yii\grid\Column;
use backend\models\InventoryActs;
use backend\models\DeviceType;

$owner = $model->ownerEmployee->snp;
$exec = $model->execEmployee->snp;
?>

<h3> Акт инвентаризации №<?= $model->id ?> от <?= $model->act_date ?></h3>
<p>РМ №<?= $model->workplace->id ?>
    Помещение:<?= $model->workplace->room->room_title ?> <?= $model->workplace->workplaces_title ?></p>
<p>Ответственный: <?= $owner ?> (<?= $model->ownerEmployee->job_title ?>)</p>
<?php if ($oldModel) { ?>
    <p>Предыдущая инвентаризация: Акт №<?= $oldModel->id ?> от <?= $oldModel->act_date ?></p>
<?php } ?>
<p>Инвентаризацию провел: <?= $exec ?></p>
<br>
<p>Устройства на рабочем месте:</p>
<?php
//набор колонок одинаков для всех, если надо то можно переоределить в каждой таблице
$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    //'sort',
    ['attribute' => 'type_id',
        //'header' => 'Тип устройства',
        'value' => function ($devModel) {
            return DeviceType::getTitle($devModel['type_id']);
        }
    ],
    ['attribute' => 'brand',
        //'header' => 'Брэнд'
    ],
    ['attribute' => 'model',
        //'header' => 'Модель'
    ],
    ['attribute' => 'sn',
        'header' => 'SN, MAC, imei',
        'value' => function ($model) {
            $sn = $model->sn;
            if (strtolower(substr($sn, 0, 2)) == 'sn')
                $sn = '';
            $return = ($sn ? $sn . ', ' : '')
                . ($model->netints ? $model->netints[0]->mac : '')
                . ($model->imei1 ? $model->imei1 : '');
            return $return;
        }
    ],
    ['attribute' => 'id',
        //'header' => 'ID'
    ],
    ['attribute' => 'specification',
        //'header' => 'Спецификация'
    ],
    ['attribute' => 'device_note',
        //'header' => 'Заметка'
    ]
];
$options = ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 9px'];
if ($devProvider)
    echo GridView::widget([
        'dataProvider' => $devProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        'columns' => $columns,
    ]); ?>

<?php
//if ($oldModelTable){
//    echo '<p>Предыдущая инвентаризация: Акт №'. $oldModel->id .' от '. $oldModel->act_date .'</p>';
//    echo GridView::widget([
//        'dataProvider' => $oldModelTable,
//        'layout' => '{items}',
//        'tableOptions' => $options,
//        //'rowOptions' =>
//        'columns' => $columns,
//    ]);
//}
?>
<?php
echo '<p>Устройства, перемещенные на другие рабочие места:</p>';
if ($consModelProvider->models) {
    echo GridView::widget([
        'dataProvider' => $consModelProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo 'Не найдено...' ?>
<?php
echo '<p>Добавленные устройства:</p>';
if ($newDevProvider->models) {
    echo GridView::widget([
        'dataProvider' => $newDevProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo 'Не найдено...' ?>

<?php
echo '<p>Потеряные устройства:</p>';
if ($lostDevProvider->models) {
    echo GridView::widget([
        'dataProvider' => $lostDevProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo 'Не найдено...' ?>
<br>
<p>Ответственный: ______________ <?= $owner ?></p>
<p>Инвентаризацию провел: ______________ <?= $exec ?></p>