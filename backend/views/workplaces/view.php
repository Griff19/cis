<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\grid\Column;
//use yii\bootstrap\Modal; //если нужно будет показать картинку
use backend\models\Workplaces;
use backend\models\Images;
use backend\models\DeviceType;
use backend\models\Devices;
use backend\assets\MapAsset;
use backend\models\Coordinate;
use yii\widgets\Pjax;

MapAsset::register($this);

/**
 * @var $this yii\web\View
 * @var $model backend\models\Workplaces
 */

$this->title = $model->workplaces_title;
$this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style> .glyphicon-map-marker{font-size: 20px} </style>
<div class="workplaces-view" style="position: relative">
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-push-8">
            <div style="float:right">
                <?php if (Yii::$app->user->can('sysadmin')) {
                    echo Html::a('<span class="glyphicon glyphicon-map-marker"></span>',
                        ['coordinate/set-coord', 'id_wp' => $model->id, 'branch' => $model->branch_id],
                        ['class' => 'btn btn-default', 'title' => 'Установить координаты', 'style' => 'padding: 5px 8px 3px 6px']);
                    echo ' ';
                    echo Html::a('<span class="glyphicon glyphicon-user"></span>', ['employees/index', 'mode' => 'select', 'id_wp' => $model->id],
                        ['class' => 'btn btn-primary', 'title' => 'Добавить пользователя']);
                    echo ' ';
                    echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id],
                        ['class' => 'btn btn-default', 'title' => 'Редактировать']);
                    echo ' ';
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger', 'title' => 'Удалить',
                        'data' => ['confirm' => 'Уверенны что хотите удалить это рабочее место?', 'method' => 'post'],
                    ]);
                    echo ' ';
                }
                echo Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['inventory-acts/index', 'id_wp' => $model->id],
                    ['class' => 'btn btn-primary', 'title' => 'Инвентаризация']);
                ?>
            </div>
            <br />
            <h3><?= Html::encode($this->title) ?></h3>
            <div class="employees-wp-view">
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
                    ['class' => Column::class, //3
                        'content' => function ($modem) use ($model) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['wpowners/delete',
                                'workplace_id' => $modem->workplace_id,
                                'employee_id' => $modem->employee_id,
                                'id_wp' => $model->id],
                                ['title' => 'Удалить ответственого...', 'data-method' => 'post']);
                        },
                        'options' => ['style' => 'width:10px']
                    ],
                    ['class' => Column::class, //4
                        'content' => function ($wpowner) {
                            /* @var $wpowner \backend\models\WpOwners */
                            if ($wpowner->status == 1)
                                return '';
                            else
                                return Html::a('<span class="glyphicon glyphicon-pushpin"></span>',
                                    ['wpowners/directwp', 'workplace_id' => $wpowner->workplace_id, 'employee_id' => $wpowner->employee_id],
                                    ['title' => 'Закрепить это рабочее место за пользователем...']);
                        },
                        'options' => ['style' => 'width:10px']
                    ]
                ];

                if (Yii::$app->user->can('sysadmin')) {
                } else {
                    unset($colums[3]);
                    unset($colums[4]);
                } ?>

                <?= GridView::widget([
                    'dataProvider' => $employeeProvider,
                    'tableOptions' => ['class' => 'table-condensed'],
                    'layout' => "{items}",
                    'columns' => $colums,
                ]); ?>
            </div>
            <div>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-condensed'],
                    'attributes' => [
                        'id',
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
                        [
                            'attribute' => 'mu',
                            'label' => 'Многопользовательское',
                            'format' => 'boolean'
                        ]
                    ],
                ]) ?>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-md-pull-4">
            <!-- отображение картинки пока отключено в пользу карты
            <div class="img-thumbnail" style="margin-top: 20px">
                <?php
                $key = md5('workplace' . $model->id);
                echo Html::img('/admin/' . Images::getLinkfile($key), ['width' => '200px', 'alt' => 'Отсутствует изображение']) . '<br>';
                if (Yii::$app->user->can('admin'))
                    echo Html::a('Изменить', ['images/index', 'owner' => $key, 'owner_id' => $model->id, 'target' => 'workplaces/view']);
                ?>
            </div>
            -->
            <div id="map" class="map"></div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#devices" data-toggle="tab">Закрепленные устройства</a></li>
    <li><a href="#advance" data-toggle="tab">Дополнително</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane" id="advance">
        <h4> Внутренние номера </h4>
        <?= GridView::widget([
            'dataProvider' => $voipProvider,
            'layout' => "{items}",
            'columns' => [
                'id',
                'voip_number',
                [
                    'attribute' => 'secret',
                    'value' => function ($voip) {
                        return Html::a($voip->secret ? '* * *' : '', '', ['title' => $voip->secret]);
                    },
                    'format' => 'raw',
                    'visible' => Yii::$app->user->can('it'),
                ],
                'description',
                'context',
                ['class' => Column::class,
                    'content' => function ($voip) use ($model) {
                        if (Yii::$app->user->can('sysadmin'))
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
        <?php if (Yii::$app->user->can('sysadmin')) { ?>
            <?= Html::a('Добавить номер', ['voipnumbers/index', 'id_wp' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } ?>
    
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
    <div class="tab-pane active" id="devices">
        <div class="devices-index">
            <h4> Закрепленные устройства: </h4>
    
            <?php if (Yii::$app->user->can('sysadmin')) { ?>
                <?= Html::a('Добавить устройство',
                    ['devices/index',
                        'mode' => 'wps',
                        'target' => 'devices/addtowp',
                        'target_id' => $model->id,
                        'id_wp' => $model->id],
                    ['class' => 'btn btn-success', 'title' => 'Выбрать из списка устройств...']) ?>
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
                'id' => 'devices',
                'dataProvider' => $deviceProvider,
                'filterModel' => $searchDeviceModel,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'rowOptions' => function ($model) use ($arrParent) {
                    if (in_array($model['id'], $arrParent)){
                        return [
                            'class' => 'info',
                            'id' => 'row'.$model->id,
                            'data-target' => '/admin/devices/view-table-comp?id_par=' . $model->id
                        ];
                    } else {
                        return null;
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
                    ['class' => Column::class,
                        'content' => function ($moddev) use ($model){
                            if (Yii::$app->user->can('sysadmin'))
                                return Html::a('',['devices/delfromwp', 'id' => $moddev['id'], 'id_wp' => $model->id],['class' => 'cross']);
                            else
                                return '';
                        }
                    ]
                ],
            ];
            $this->registerAssetBundle('backend\assets\CollapseTableAsset');
            Pjax::begin(['id' => 'workplaces_device']);
            echo GridView::widget($col1);
            $this->registerJs('CollapseTable();');
            Pjax::end();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    /**
     * Скрипт готовит данные для формирования карты и меток на ней.
     * @var points array массив хранит данные о метках, устанавливаемых на карте
     * @var branch integer идентификатор филиала для которого формируется карта
     * @var floor integer номер отображаемого этажа. По умолчанию 1, а если стоит точка то берется этаж точки
     * @var max_zoom integer максимальный зум для текущей карты
     * @var pic_width integer ширина текущей карты
     * @var pic_height integer высота текущей карты
     */
    var points = [];
    var branch = <?= $model->branch_id ?>;
    var max_zoom = <?= Coordinate::getMapParams($model->branch_id)['max_zoom'] ?>;
    var pic_width = <?= Coordinate::getMapParams($model->branch_id)['pic_width'] ?>;
    var pic_height = <?= Coordinate::getMapParams($model->branch_id)['pic_height']?>;
    <?php if ($model->coordinate) { ?>
        var floor = <?= $model->coordinate[0]->floor ?>;
        <?php foreach ( $model->coordinate as $coordinate) {
            if ( ctype_space($coordinate->preset) || empty($coordinate->preset) )
                $preset = 'islands#blueDotIcon';
            else
                $preset = trim($coordinate->preset);
            if (ctype_space($coordinate->content) || empty($coordinate->content))
                $content = '';
            else
                $content = trim($coordinate->content);
        ?>
            points.push({y: <?= $coordinate->y ?>, x: <?= $coordinate->x ?>, balloonContent: '<?= $model->workplaces_title . '<br>' .$coordinate->balloon ?>', preset: '<?= $preset ?>', content: '<?= $content?>'});
        <?php } ?>
    <?php } else { ?>
        var floor = 1;
    <?php }
    $allCoord = Workplaces::getAllCoordinate($model->coordinate ? $model->coordinate[0]->floor : 1, $model->branch_id);
    foreach ( $allCoord as $coordinate ){
        if ( ctype_space($coordinate->preset) || empty($coordinate->preset) )
            $preset = 'islands#blueDotIcon';
        else
            $preset = trim($coordinate->preset);
    ?>
        points.push({y: <?= $coordinate->y ?>, x: <?= $coordinate->x ?>, balloonContent: '<?= $coordinate->balloon ?>', preset: '<?= $preset ?>'})
    <?php } ?>
</script>



