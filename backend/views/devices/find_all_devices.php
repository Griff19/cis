<?php
/**
 * @var $employee_id int
 */
use yii\grid\GridView;
use yii\helpers\Html;
use backend\models\Images;
use backend\models\Employees;
use yii\widgets\Pjax;

?>

<div style="margin: 5px">
    <div class="img-thumbnail" style="margin-top: 20px">
        <?php
        $key = md5('workplace' . 'find-all-devices');
        echo Html::img('/admin/' . Images::getLinkfile($key), ['width' => '350px', 'alt' => 'Отсутствует изображение']) . '<br>';
        if (Yii::$app->user->can('admin'))
            echo Html::a('Изменить', ['images/index',
                'owner' => $key,
                //'target' => 'devices/find-all-devices?employee_id=' . $employee_id]);
                'target' => 'devices/find-all-devices']);
        ?>
    </div>
    <p> Выборка по:
    <?= Html::a('Соседов С.А.', ['find-all-devices', 'employee_id' => 4193], ['style' => $employee_id == 4193 ? 'font-weight:bold' : ''])?>;
    <?= Html::a('Лучинкин А.В.', ['find-all-devices', 'employee_id' => 3695], ['style' => $employee_id == 3695 ? 'font-weight:bold' : ''])?>;
    <?= Html::a('Ярцев Е.А.', ['find-all-devices', 'employee_id' => 4446], ['style' => $employee_id == 4446 ? 'font-weight:bold' : ''])?>;
    <?= Html::a('Важин Ю.А.', ['find-all-devices', 'employee_id' => 3133], ['style' => $employee_id == 3133 ? 'font-weight:bold' : ''])?>;
    <?= Html::a('Все', ['find-all-devices'], ['style' => empty($employee_id) ? 'font-weight:bold' : ''])?>;
    </p>
    <div class="find-all-devices">
        <h3>
        <?php if (!empty($employee_id))
            echo Employees::findOne($employee_id)->snp;
        else
            echo 'Все';
        ?>
        </h3>
        <h4> Устройства на складе: </h4>

        <?php

        //var_dump($arrParent);

        $col1 = [
            'dataProvider' => $deviceProvider,
            'filterModel' => $searchDeviceModel,
            'filterUrl' => 'find-all-devices',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'rowOptions' => function ($model) use ($arrParent) {
                if (in_array($model['id'], $arrParent)){
                    return [
                        'class' => 'info',
                        'id' => 'row'.$model['id'],
                        'data-target' => '/admin/devices/view-table-comp?id_par=' . $model['id'] . '&mode=0'
                    ];
                } else {
                    return '';
                }
            },
            'layout' => "{items}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'workplace_id', 'label' => 'ID РМ'],
                ['attribute' => 'workplaces_title', 'label' => 'Зона хранения',
                    'content' => function($model){
                        $res = str_replace('Зона хранения', '', $model['workplaces_title']);
                        return '<b>'.$res.'</b>';
                    }],
                ['attribute' => 'id', 'label' => 'ID Устройства'],
                ['attribute' => 'title', 'label' => 'Тип Устройства'],
                'device_note',
                'brand',
                'model',
                'sn',
                'specification'
            ],
        ];

        $this->registerAssetBundle('backend\assets\CollapseTableAsset');
        Pjax::begin();
        echo GridView::widget($col1);
        $this->registerJs('CollapseTable();');
        Pjax::end();
        ?>

    </div>
</div>
