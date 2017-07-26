<?php
/**
 * @var $employee_id int
 */
use yii\grid\GridView;
use yii\helpers\Html;
use backend\models\Images;
use backend\models\Employees;

?>

<div style="margin: 5px">
    <div class="img-thumbnail" style="margin-top: 20px">
        <?php
        $key = md5('workplace' . 'find-all-devices');
        echo Html::img('/admin/' . Images::getLinkfile($key), ['width' => '200px', 'alt' => 'Отсутствует изображение']) . '<br>';
        if (Yii::$app->user->can('admin'))
            echo Html::a('Изменить', ['images/index',
                'owner' => $key,
                'target' => 'devices/find-all-devices?employee_id=' . $employee_id]);
        ?>
    </div>
    <p> Выборка по:
    <?= Html::a('Соседов С.А.;', ['find-all-devices', 'employee_id' => 4193])?>
    <?= Html::a('Лучинкин А.В.;', ['find-all-devices', 'employee_id' => 3695])?>
    <?= Html::a('Ярцев Е.А.;', ['find-all-devices', 'employee_id' => 4446])?>
    <?= Html::a('Важин Ю.А.;', ['find-all-devices', 'employee_id' => 3133])?>
    <?= Html::a('Все;', ['find-all-devices'])?>
    </p>
    <div class="find-all-devices">
        <h3>
        <?php if (!empty($employee_id))
            echo Employees::findOne($employee_id)->snp;
        else
            echo 'Все';
        ?>
        </h3>
        <h4> Закрепленные устройства: </h4>

        <?php

        //var_dump($arrParent);

        $col1 = [
            'dataProvider' => $deviceProvider,
            'filterModel' => $searchDeviceModel,
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'rowOptions' => function ($model) use ($arrParent) {
                if (in_array($model['id'], $arrParent)){
                    return [
                        'class' => 'info',
                        'id' => 'row'.$model['id'],
                        'data-target' => '/admin/devices/view-table-comp?id=' . $model['id'] . '&mode=0'
                    ];
                } else {
                    return '';
                }
            },
            'layout' => "{items}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'employee_id', 'label' => 'ID Сотрудника'],
                ['attribute' => 'workplace_id', 'label' => 'ID РМ'],
                ['attribute' => 'workplaces_title', 'label' => 'Рабочее место'],
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
        \yii\widgets\Pjax::begin();
        echo GridView::widget($col1);
        $this->registerJs('CollapseTable();');
        \yii\widgets\Pjax::end();
        ?>

    </div>
</div>
