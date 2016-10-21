<?php

use backend\models\Branches;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'Система IT';

?>

<div class="row">
    <div class="bg-info" style="text-align: center">
        <b>Звоните в отдел информатизации по всем техническим вопросам, связанным с работой компьютера:</b><br>
        Внешний номер <b>923-792-1192</b><br>
        Внутренний номер <b>192</b><br>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-5 col-md-push-7">
        <p>
            <b>Стационарные телефоны</b>
        <ul style="margin-left: 2px; padding-left: 2px;">
            <li>Исходящие вызовы идут с номера <b>923 792-05-52</b></li>
            <li>Входящие принимаются на номера горячей линии: <b>923 792-05-52</b> и бесплатный <b>8 800 550-00-52</b>
            </li>
            <li>Ваш внутренний номер необходимо сообщить контрагентам, чтобы они могли напрямую выходить
                на связь с вами, дозвонившись на горячую линию и набрав внутренний номер абонента.
            </li>
        </ul>
        </p>
    </div>
    <div class = "col-xs-12 col-md-7 col-md-pull-5">
        <p>
            <b>Корпоративные сим-карты</b>
        <ul style="margin-left: 2px; padding-left: 2px;">
            <li>Ежедневно проверяйте состояние счета командой: <b>*105*98#</b></li>
            <li>В случае утери сим-карты, сообщите в отдел информатизации.</li>
            <li>Не допускайте попадания корпоративной сим-карты в распоряжение третьих лиц!</li>
            <li>Не пополняйте счет самостоятельно!</li>
            <li>Не используйте передачу данных, если у вас не подключены опции безлимитного интернет.</li>
            <li>Не используйте передачу данных за пределами домашнего региона, если у вас не подключена
                опция Интернет по России.
            </li>
        </ul>
        </p>
    </div>
</div>

<?php
Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pager' => [
        'prevPageLabel' => 'Назад',
        'nextPageLabel' => 'Далее',
        'maxButtonCount' => 20
    ],
    'rowOptions' => function ($model){
        return $model->status == 0 ? ['class' => 'danger'] : '';
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'branch_title',
            'value' => 'branch_title',
            'filter' => ArrayHelper::map(Branches::find()->orderBy('id')->all(), 'branch_title', 'branch_title')
        ],
        [
            'attribute' => 'snp',
            'value' => function ($model) {
                //var_dump($model);
                return Html::a($model->snp, ['employees/view', 'id' => $model->emp_id, 'mode' => 'start']);
            },
            'format' => 'raw'
        ],
        'cell_number', 'voip_number', 'email_address', 'room_title',
        //'workplaces_title',
        [
            'attribute' => 'workplaces_title',
            'value' => function ($arr) {
                return Html::a($arr['wp_id'], ['workplaces/view', 'id' => $arr['wp_id']]);
            },
            'format' => 'raw'
        ],
        'job_title'
    ]
]);
Pjax::end();
?>




