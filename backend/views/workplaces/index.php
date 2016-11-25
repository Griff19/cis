<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use dosamigos\datepicker\DatePicker;
use yii\jui\DatePicker;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WorkplacesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рабочие места';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplaces-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //чтобы таблицу можно было отсортировать и при этом в запросе остались значения mode и id_dev сохраняем их
    //для этого можно использовать сессию но это потом
        $param = Yii::$app->request->queryParams;
        $query = '';
        if($param) $query = 'mode=' . ArrayHelper::getValue($param, 'mode')
            . '&id_dev=' . ArrayHelper::getValue($param, 'id_dev');
    ?>

    <p>
        <?php
        if (Yii::$app->user->can('admin'))
            echo Html::a('Создать рабочее место', ['create'], ['class' => 'btn btn-success', 'style' => 'margin-bottom: 5px'])
        ?><br>
        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Общий склад', ['index?'. $query .'&WorkplacesSearch%5Broom_id%5D=Каб.17+%28ИТ-служба%3B+склад%29'], ['class' => 'btn btn-default'])?>
        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Установленные комплектующие', ['index?'. $query .'&WorkplacesSearch%5Bworkplaces_title%5D=установленные+комплектующие'], ['class' => 'btn btn-default'])?>
        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Неисправное оборудование', ['index?' . $query . '&WorkplacesSearch%5Bworkplaces_title%5D=неисправное+оборудование'], ['class' => 'btn btn-default'])?>
        <?= Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Потерянные', ['index?' . $query . '&WorkplacesSearch%5Bworkplaces_title%5D=потерянные'], ['class' => 'btn btn-default'])?>
        <?= Html::a(Html::img('/admin/img/cross.png',['width' => '16px']) . 'Сбросить фильтр', ['index?' . $query], ['class' => 'btn btn-default'])?>

    </p>

    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'], //0
        ['attribute' => 'id', //1
            'options' => ['style' => 'width:30px'],
        ],
        // подразделение
        [ //2
            'attribute' => 'branch_id',
            'value' => 'branch.branch_title'
        ],
        // кабинет, отдел
        [ //3
            'attribute' => 'room_id',
            'value' => 'room.room_title'
        ],
        // описание рабочего места
        [ //4
            'attribute' => 'workplaces_title',
            'value' => function($model){
                return Html::a($model->workplaces_title, ['workplaces/view', 'id' => $model->id]);
            },
            'format' => 'raw',
        ],
        // ответственный
        [ //5
            'attribute' => '_owner',
            'value' => function($model){
                if ($model->owner) return $model->owner[0]['snp'];
                else return '-';
            },
            'format' => 'raw'
        ],
        // признак многопользовательского места
        //'mu:boolean',
        //при установке устройства на рабочее место:
        ['class' => \yii\grid\Column::className(), //6
            'content' => function($model) use ($id_dev, $target, $target_id){
                return Html::a(Html::img('/admin/img/ok.png',['width' => '25px']),
                   // ['devices/addtowp', 'id' => $id_dev, 'id_wp' => $model->id]);
                    ['select', 'id' => $model->id, 'target' => $target, 'target_id' => $target_id]);
            }
        ],
        ['attribute' => 'inventoryDate', //7
            'value' => function ($model) {
                return $model->inventoryDate ? $model->inventoryDate : '-';
            },
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'inventoryDate',
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'format' => 'yyyy-MM-dd',
                ]
            ]),
            'options' => ['style' => 'width:116px']
        ],
        ['class' => 'yii\grid\ActionColumn', //8
            'template' => '{view}'
        ],
    ];

    if ($mode == 'sel') unset($columns[7], $columns[8]); //удаляем лишние колонки
    else unset($columns[6]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
