<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\grid\Column;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $model backend\models\Devices */

$this->title = $model->id . ' ';
$this->title .= ($model->deviceType ? $model->deviceType->title : '') . ' ';
$this->title .=  $model->device_note ? '('. $model->device_note .')' : '';
$id_wp = Yii::$app->session->get('id_wp');
if ($id_wp != 0) {
    $this->params['breadcrumbs'][] = ['label' => 'Рабочее место', 'url' => ['workplaces/view', 'id' => $id_wp]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-view">

    <h1><?= Html::encode($this->title) ?> <?= $model->fake_device > $model::DEVICE_DEF
            ? '<a title="Зарезервированное устройство"><span class="glyphicon glyphicon-warning-sign text-warning"></span></a>'
            : '' ?> </h1>

    
    <?= Html::a('Редактировать', ['update', 'id' => $model->id, ], ['class' => 'btn btn-primary', 'data-method' => 'POST']) ?>
    
    <?= ButtonDropdown::widget([
        'label' => 'Назначить рабочее место...',
        'dropdown' => [
            'items' => [
                ['label' => 'Немедленно', 'url' => ['workplaces/index', 'mode' => 'sel', 'target' => 'devices/addtowp', 'target_id' => $model->id]],
                ['label' => 'Виртуально', 'url' => ['tmp-moving/create', 'device_id' => $model->id, 'workplace_from' => $model->workplace_id]],
            ],
        ],
    ]);?>
    
    <?= Html::a('История', ['storydevice/index', 'id_dev' => $model->id])?>
    
    <?php if (Yii::$app->user->can('admin'))
        echo Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'style' => 'float:right',
            'data' => [
                'confirm' => 'Удалить '. $this->title .'?',
                'method' => 'post',
            ],
        ]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'deviceType.title',
            'brand',
            'model',
            'sn',
            'device_note',
            'specification',
            'imei1',
            'imei2',
            [
                'attribute' => 'workplace_id',
                'value' => isset($model->workplace) ? Html::a($model->workplace->summary, ['workplaces/view', 'id' => $model->workplace_id]) : '-',
                'format' => 'raw'
            ],
            [
                'attribute' => 'parent_device_id',
                'value' => Html::a($model->parent_device_id, ['devices/view', 'id' => $model->parent_device_id]),
                'format' => 'raw'
            ]
        ],
    ]) ?>
    <?= Html::button('Комплектующие...', ['class' => 'btn btn-default btn-block', 'data-toggle' => 'collapse', 'data-target' => '#index-comp'])?>
    <div class="collapse" id="index-comp">

    <?php

    if ($compProvider->models){
        echo GridView::widget([
            'dataProvider' => $compProvider,
            'filterModel' => $compSearch,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width:60px;'],
                ],
                [
                    'attribute' => 'type_id',
                    'value' => function($model) {
                        if ($model->deviceType) {
                            return Html::a($model->deviceType->title, ['devices/view', 'id' => $model->id],
                                ['title' => 'Открыть устройство...']);
                        } else {
                            return '-';
                        }
                    },
                    'format' => 'raw'
                ],
                'brand',
                'model',
                [
                    'attribute' => 'device_note',
                    'value' => function($model) {
                        return Html::a($model->device_note, ['devices/view', 'id' => $model->id],
                            ['title' => 'Открыть устройство...']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'workplace_id',
                    'value' => function($model) {
                        if ($model->workplace)
                            return Html::a($model->workplace['workplaces_title'], ['workplaces/view', 'id' => $model->workplace_id]);
                        else
                            return '-';
                    },
                    'format' => 'raw'
                ],
                ['class' => Column::className(),
                    'content' => function($model) {
                        return Html::a(Html::img('/admin/img/cross.png', ['style' => 'height:24px']),
                            ['devices/delcomp', 'id_dev' => $model->parent_device_id, 'id_comp' => $model->id],
                            ['title' => 'Удалить из комплектующих...']);
                    }
                ],
            ],
        ]);
    }
    echo Html::a('Добавить комплектующее', ['devices/index-comp', 'mode' => 'dvs', 'target' => 'devices/addcomp', 'target_id' => $model->id, 'id_dev' => $model->id, 'id_wp' => $model->workplace_id], ['class' => 'btn btn-primary']);
    ?>
    </div>
    <br>
    <?= Html::button('Внутренние номера...', ['class' => 'btn btn-default btn-block', 'data-toggle' => 'collapse', 'data-target' => '#index-voip'])?>
    <div class="collapse" id="index-voip">
    <?php //Для устройств с типом "Телефон" отображаем номера VoIP
    if ($model->type_id == 3) {
        if ($voipProvider->models) {
            echo GridView::widget([
                'dataProvider' => $voipProvider,
                //'filterModel' => $voipSearch,
                'layout' => "{items}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'voip_number',
                    ['attribute' => 'secret',
                        'value' => function($voip) {
                            return Html::a('* * *', '', ['title' => $voip->secret]);
                        },
                        'format' => 'raw'
                    ],
                    'description',
                    'context',
                    ['class' => Column::className(),
                        'content' => function ($voip) use ($model) {
                            return Html::a('Снять', ['voipnumbers/choicenull', 'id' => $voip->id, 'id_dev' => $model->id],
                            ['title' => 'Снять номер с устройства (номер остается в базе)']);
                        },
                        'options' => ['style' => 'width:10px']
                    ],
                ],
            ]);
        }
    }
    echo Html::a('Выбрать номер VoIP', ['voipnumbers/index', 'id_dev' => $model->id, 'dev_name' => $this->title], ['class' => 'btn btn-primary']);
    ?>
    </div>
    <br>
    <?= Html::button('Сетевые интерфейсы...', ['class' => 'btn btn-default btn-block', 'data-toggle' => 'collapse', 'data-target' => '#index-ints'])?>
    <div class="collapse" id="index-ints">
    <?php
    if ($netProvider->models) {
        echo GridView::widget([
            'dataProvider' => $netProvider,
            'filterModel' => $netSearch,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'mac',
                //'ipaddr',
                [
                    'attribute' => 'ipaddr',
                    'value' => function ($net){
                        return Html::a($net->ipaddr, ['netints/update', 'id' => $net->id]);
                    },
                    'format' => 'raw'
                ],
                'domain_name',
                'port_count',
                'vendor',
                'type',
                'devices.device_note',
                'deviceType.title',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    }
    echo Html::a('Добавить сетевой интерфейс', ['netints/create', 'id_dev' => $model->id], ['class' => 'btn btn-primary']);
    ?>
    </div>
    <br>
    <?= Html::button('Дополнительные параметры...', ['class' => 'btn btn-default btn-block', 'data-toggle' => 'collapse', 'data-target' => '#index-param'])?>
    <div class="collapse" id="index-param">
    <?php //если для усройства существует список параметров - отображем его
    if ($param_model) {
        echo '<p>Параметры:</p>';
        echo DetailView::widget([
            'model' => $param_model,
            'attributes' => [
                'id_device',
                'id',
                'brend',
                'model',
                'sn',
                'mac',
                [
                    'attribute' => 'ip',
                    'value' => function ($net){
                        return Html::a($net->ip, ['netints/update', 'id' => $net->id]);
                    },
                    'format' => 'raw'
                ],
                'nport',
                'login',
                'password',
                'biospass',
                'radmin',
                'dns',
                'voip',
                'aster_pwd',
                'aster_cont',
                'imei',
            ],
        ]);
        echo Html::a('Редактировать параметры', ['parameters/update', 'id' => $param_model->id], ['class' => 'btn btn-primary']);
    } else {
        echo Html::a('Ввести параметры', ['parameters/create', 'id_dev' => $model->id, 'dev_name' => $this->title], ['class' => 'btn btn-primary']);
    }
    ?>
    </div>
</div>
