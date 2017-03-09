<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\MeetingMinutes;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\MmAgendaSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $modelDoc \backend\models\MeetingMinutes
 */

//$this->title = 'Mm Agendas';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-agenda-index">

    <h3> Повестка встречи </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Mm Agenda', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        'content:ntext',

        ['class' => 'yii\grid\ActionColumn',
            'controller' => 'mm-agenda',
            'template' => '{delete}'
        ],
    ];
    if ($modelDoc->status == MeetingMinutes::DOC_SAVE)
        array_pop($columns);

    Pjax::begin(['id' => 'mmagenda_idx']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
