<?php
/** Представление списка рабочих мест пользователей */
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use yii\grid\GridView;
use backend\models\Branches;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WorkplacesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $id_dev integer идентификатор устройства для которого выбираем РМ */
/* @var $mode string режим отображения таблицы */

$this->title = 'Рабочие места';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplaces-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //чтобы таблицу можно было отсортировать и при этом в запросе остались значения mode и id_dev сохраняем их
    //todo: для этого конечно можно использовать сессию но это потом
        $param = Yii::$app->request->queryParams;
        $query = '';
        if($param) $query = 'mode=' . ArrayHelper::getValue($param, 'mode')
            . '&id_dev=' . ArrayHelper::getValue($param, 'id_dev')
            . '&target=' . urlencode(ArrayHelper::getValue($param, 'target'))
            . '&target_id=' . ArrayHelper::getValue($param, 'target_id');
    ?>

    <p>
        <?php
        if (Yii::$app->user->can('auditor') || Yii::$app->user->can('sysadmin')) {
            echo Html::a('Создать рабочее место', ['create'], ['class' => 'btn btn-success']) . ' ';
            echo Html::a('Создать Отдел/Кабинет', ['rooms/create'], ['class' => 'btn btn-primary']) . ' ';
            echo Html::a('Создать Подразделение', ['branches/create'], ['class' => 'btn btn-primary']) . ' ';
        }
        if (yii::$app->user->can('admin'))
            echo Html::a('Найти РМ без координат', ['list-unset'], ['class' => 'btn btn-info']);
        ?>
    </p>
    <p>
        <?=Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Общий склад', ['index?'. $query .'&WorkplacesSearch%5Broom_id%5D=Каб.17+%28ИТ-служба%3B+склад%29'], ['class' => 'btn btn-default'])?>
        <?=Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Установленные комплектующие', ['index?'. $query .'&WorkplacesSearch%5Bworkplaces_title%5D=установленные+комплектующие'], ['class' => 'btn btn-default'])?>
        <?=Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Неисправное оборудование', ['index?' . $query . '&WorkplacesSearch%5Bworkplaces_title%5D=неисправное+оборудование'], ['class' => 'btn btn-default'])?>
        <?=Html::a(Html::img('/admin/img/search.png',['width' => '16px']) . 'Потерянные', ['index?' . $query . '&WorkplacesSearch%5Bworkplaces_title%5D=потерянные'], ['class' => 'btn btn-default'])?>
        <?=Html::a(Html::img('/admin/img/cross.png',['width' => '16px']) . 'Сбросить фильтр', ['index?' . $query], ['class' => 'btn btn-default'])?>
    </p>

    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'], //0
        ['attribute' => 'id', //1
            'options' => ['style' => 'width:30px'],
            'filterOptions' => ['style' => 'padding: 8px 1px 0px 1px']
        ],
        [ //2 подразделение
            'attribute' => 'branch_id',
            'value' => 'branch.branch_title',
            'filter' => ArrayHelper::map(Branches::find()->orderBy('id')->all(), 'branch_title', 'branch_title'),
        ],
        [ //3 кабинет, отдел
            'attribute' => 'room_id',
            'value' => 'room.room_title'
        ],
        [ //4 описание рабочего места
            'attribute' => 'workplaces_title',
            'value' => function($model){
                return Html::a($model->workplaces_title, ['workplaces/view', 'id' => $model->id]);
            },
            'format' => 'raw',
        ],
        [ //5 ответственный
            'attribute' => '_owner',
            'value' => function($model){
                if ($model->owner) return $model->owner[0]['snp'];
                else return '-';
            },
            'format' => 'raw'
        ],
        // признак многопользовательского места
        //'mu:boolean',
        [ //6 активно при установке устройства на рабочее место:
            'class' => \yii\grid\Column::class,
            'content' => function($model) use ($id_dev, $target, $target_id){
                return Html::a('<span class="glyphicon glyphicon-ok"></span>',
                    ['select', 'id' => $model->id, 'target' => $target, 'target_id' => $target_id, 'id_dev' => $id_dev]);
            }
        ],
        [ //7 дата инвентаризации
            'attribute' => 'inventoryDate',
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

    if ($mode == 'sel') unset($columns[7], $columns[8]); //удаляем лишние, при данных условиях, колонки
    else unset($columns[6]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
