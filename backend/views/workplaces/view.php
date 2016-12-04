<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\grid\Column;
use yii\bootstrap\Modal;
use backend\models\Workplaces;
use backend\models\Images;
use backend\models\DeviceType;
use backend\models\Devices;

/**
 * @var $this yii\web\View
 * @var $model backend\models\Workplaces
 *
 **/

$this->title = $model->workplaces_title;
$this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplaces-view" style="position: relative">
    <div class="row">
        <div class="col-xs-12 col-md-9 col-md-push-3">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?php if (Yii::$app->user->can('admin')) {
                    echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'style' => 'float:right',
                        'data' => [
                            'confirm' => 'Уверенны что хотите удалить это рабочее место?',
                            'method' => 'post',
                        ],
                    ]);
                    echo ' ';
                }

                echo Html::a('Инвентаризация', ['inventory-acts/index', 'id_wp' => $model->id], ['class' => 'btn btn-primary']);
                ?>
            </p>

            <div class="employees-wp-view" style="float: left; /*width: 50%;*/ margin-bottom: 10px">
                <?= Html::a('История', ['storyworkplace/index', 'id_wp' => $model->id]) ?>
                <?php $colums = [
                    ['class' => 'yii\grid\SerialColumn'], //0
                    [ //1
                        'attribute' => 'date',
                        'value' => 'date',
                        'options' => ['style' => 'width:93px'],

                    ],
                    [ //2
                        'attribute' => 'employee_id',
                        'value' => function ($wpowner) {
                            return Html::a($wpowner->employee->snp, ['employees/view', 'id' => $wpowner->employee_id]);
                        },
                        'format' => 'raw'
                    ],
                    ['class' => Column::className(), //3
                        'content' => function ($modem) use ($model) {
                            return Html::a('', ['wpowners/delete',
                                'workplace_id' => $modem->workplace_id,
                                'employee_id' => $modem->employee_id,
                                'id_wp' => $model->id],
                                ['class' => 'cross',
                                    'title' => 'Удалить ответственого...', 'data-method' => 'post']);
                        },
                        'options' => ['style' => 'width:10px']
                    ],
                    ['class' => Column::className(), //4
                        'content' => function ($wpowner) {
                            /* @var $wpowner \backend\models\WpOwners */
                            if ($wpowner->status == 1) return '';
                            else
                                return Html::a(Html::img('/admin/img/note.png', ['style' => 'width:24px']),
                                    ['wpowners/directwp', 'workplace_id' => $wpowner->workplace_id, 'employee_id' => $wpowner->employee_id],
                                    ['title' => 'Закрепить это рабочее место за пользователем...']);
                        },
                        'options' => ['style' => 'width:10px']
                    ]
                ];

                if (Yii::$app->user->can('admin')) {
                } else {
                    unset($colums[3]);
                    unset($colums[4]);
                } ?>

                <?= GridView::widget([
                    'dataProvider' => $employeeProvider,
                    'layout' => "{items}",
                    'columns' => $colums,
                ]); ?>

                <?php if (Yii::$app->user->can('admin')) { ?>
                    <br>
                    <?= Html::a('Добавить пользователя', ['employees/index', 'mode' => 'select', 'id_wp' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php } ?>

            </div>
        </div>
        <div class="col-xs-12 col-md-3 col-md-pull-9">

            <div class="img-thumbnail" style="margin-top: 20px">
                <?php
                $key = md5('workplace' . $model->id);
                echo Html::img('/admin/' . Images::getLinkfile($key), ['width' => '200px', 'alt' => 'Отсутствует изображение']) . '<br>';
                if (Yii::$app->user->can('admin'))
                    echo Html::a('Изменить', ['images/index', 'owner' => $key, 'owner_id' => $model->id, 'target' => 'workplaces/view']);
                ?>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'room_id',
            [
                'attribute' => 'branch_id',
                'value' => $model->branch ? $model->branch->branch_title : '-',
                'format' => 'raw'
            ],
            [
                'attribute' => 'room_id',
                'value' => $model->room ? $model->room->room_title : '-',
                'format' => 'raw'

            ],
            'workplaces_title',
            //'mu:boolean',
            [
                'label' => 'Многопользовательское рабочее место',
                'value' => $model->mu ? 'Да' : 'Нет'
            ]
        ],
    ]) ?>

</div>
<div style="margin: 5px">
    <h4> Внутренние номера </h4>
    <?= GridView::widget([
        'dataProvider' => $voipProvider,
        'layout' => "{items}",
        'columns' => [
            'id',
            'voip_number',
            //'secret',
            [
                'attribute' => 'secret',
                'value' => function ($voip) {
                    return Html::a($voip->secret ? '* * *' : '', '', ['title' => $voip->secret]);
                },
                'format' => 'raw'
            ],
            'description',
            'context',
            ['class' => Column::className(),
                'content' => function ($voip) use ($model) {
                    if (Yii::$app->user->can('admin'))
                        return Html::a('', ['voipnumbers/choicenull', 'id' => $voip->id, 'id_dev' => 0, 'id_wp' => $model->id],
                            ['class' => 'cross',
                                'title' => 'Снять номер с рабочего места (остается в базе)']);
                    else
                        return '';
                }
            ]
        ]
    ])
    ?>
    <?php if (Yii::$app->user->can('admin')) { ?>
        <?= Html::a('Добавить номер', ['voipnumbers/index', 'id_wp' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php } ?>
</div>
<div style="margin: 5px">
    <h4> Сетевые интерфейсы </h4>
    <?= GridView::widget([
        'dataProvider' => Workplaces::getNetintsProvider($model->id),
        'layout' => "{sorter}\n{pager}\n{items}",
        'columns' => [
            ['attribute' => 'title',
                'header' => 'Тип устройства',
                'value' => 'title'
            ],
            ['attribute' => 'dev_id',
                'header' => 'Устройство',
                'value' => function ($arr) {
                    return Html::a($arr['dev_id'], ['devices/view', 'id' => $arr['dev_id']]);
                },
                'format' => 'raw'
            ],
            ['attribute' => 'ip',
                'header' => 'IP адрес',
                'value' => 'ip'
            ]
        ]
    ])
    ?>
</div>
<div style="margin: 5px">

    <div class="devices-index">
        <h4> Закрепленные устройства: </h4>

        <?php if (Yii::$app->user->can('admin')) { ?>
            <?= Html::a('Добавить устройство', ['devices/index', 'mode' => 'wps', 'target' => 'devices/addtowp', 'target_id' => $model->id, 'id_wp' => $model->id], ['class' => 'btn btn-success']) ?>
            <?= Html::button('Дополнительно >>>', ['class' => 'btn btn-default', 'data-toggle' => 'collapse', 'data-target' => '#accordion']) ?>
            <div id="accordion" class="collapse" style="float: right">
                <form name="test" action="/it_base/backend/web/index.php?r=devices/autocreate&id_wp= <?= $model->id ?> "
                      method="post">
                    <label><input type="checkbox" name="sys" value="1"> Системный блок </label><br>
                    <label><input type="checkbox" name="mon" value="1"> Монитор </label><br>
                    <label><input type="checkbox" name="tel" value="1"> Телефон </label><br>
                    <label><input type="checkbox" name="ibp" value="1"> ИБП </label><br>
                    <?= Html::submitButton('Создать автоматом', ['class' => 'btn btn-success']) ?>
                </form>
            </div>
        <?php } ?>
        <?php
        //формируем массив с идентификаторами родителей
        $arrParent = Devices::arrayParentId($model->id);
        //var_dump($arrParent);

        $col1 = [
            'dataProvider' => $deviceProvider,
            'filterModel' => $searchDeviceModel,
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'rowOptions' => function ($model) use ($arrParent) {
                if (in_array($model['id'], $arrParent)){
                    return [
                        'class' => 'info',
                        'id' => 'row'.$model->id,
                        'data-target' => '/admin/devices/view-table-comp?id=' . $model->id
                    ];
                } else {
                    return '';
                }
            },
            'layout' => "{items}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'sort',
                'id',
                ['attribute' => 'type_id',
                    'value' => function ($model){
                        return '<b>'. Html::a(DeviceType::getTitle($model['type_id']), ['devices/view', 'id'=> $model['id']]) .'</b>';
                    },
                    'format' => 'raw'
                ],
                //'type_id',
                'device_note',
                'brand',
                'model',
                'sn',
                'specification',
                'parent_device_id',
                ['class' => Column::className(),
                    'content' => function ($moddev) use ($model){
                        if (Yii::$app->user->can('admin'))
                            return Html::a('',['devices/delfromwp', 'id' => $moddev['id'], 'id_wp' => $model->id],['class' => 'cross']);
                        else
                            return '';
                    }
                ]
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
