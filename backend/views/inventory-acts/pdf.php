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
use backend\models\InventoryActs;
use backend\models\DeviceType;

$owner = $model->ownerEmployee->snp;
$exec = $model->execEmployee->snp;
?>

<h3> Акт инвентаризации №<?= $model->id ?> от <?= Yii::$app->formatter->asDate($model->act_date, 'dd.MM.yyyy') ?></h3>
<p>
    РМ №<?= $model->workplace->id ?><br/>
    Помещение:<?= $model->workplace->room->room_title ?> <?= $model->workplace->workplaces_title ?><br/>
    Ответственный: <?= $owner ?> (<?= $model->ownerEmployee->job_title ?>)<br/>
<?php if ($oldModel) { ?>
    Предыдущая инвентаризация: Акт №<?= $oldModel->id ?> от <?= $oldModel->act_date ?><br/>
<?php } ?>
    Инвентаризацию провел: <?= $exec ?>
</p>
<h4>Устройства на рабочем месте:</h4>
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
echo '<h4>Устройства, перемещенные на другие рабочие места:</h4>';
if ($consModelProvider) {
    echo GridView::widget([
        'dataProvider' => $consModelProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo '<p style = "font-size: 9px">Не найдено...</p>' ?>
<?php
echo '<h4>Добавленные устройства:</h4>';
if ($newDevProvider) {
    echo GridView::widget([
        'dataProvider' => $newDevProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo '<p style = "font-size: 9px">Не найдено...</p>' ?>

<?php
echo '<h4>Потеряные устройства:</h4>';
if ($lostDevProvider) {
    echo GridView::widget([
        'dataProvider' => $lostDevProvider,
        'layout' => '{items}',
        'tableOptions' => $options,
        //'rowOptions' =>
        'columns' => $columns,
    ]);
} else echo '<p style = "font-size: 9px">Не найдено...</p>' ?>
<br>
<p>Ответственный: ______________ <?= $owner ?></p>
<p>Инвентаризацию провел: ______________ <?= $exec ?></p>