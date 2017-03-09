<?php

use backend\models\MeetingMinutes;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\MmDecisionSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $modelDoc MeetingMinutes;
 */


?>

<div class="mmdecision-index" style="margin-top: 40px">

    <h3> Принятые решения </h3>

    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        'content:ntext',
        'due_date:date',

        ['class' => 'yii\grid\ActionColumn',
            'controller' => 'mm-decision',
            'template' => '{delete}'
        ],
    ];
    if ($modelDoc->status == MeetingMinutes::DOC_SAVE)
        array_pop($columns);

    Pjax::begin(['id' => 'mmdecision_idx']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?></div>
