<?php
/**
 * Представление "Участники встречи"
 * Встраивается в представление "Протокол встречи" (meeting-minutes\view.php)
 * Выводит список сотрудников, учавствующих во встрече
 */

use backend\models\MeetingMinutes;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\MmParticipantsSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $modelDoc
 */

?>
<div class="mm-participants-index">

    <h3> Участники встречи: </h3>

    <?php
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'employee_name',
            'value' => 'employee.snp'
        ],
        ['class' => 'yii\grid\ActionColumn',
            'controller' => 'mm-participants',
            'template' => '{delete}'
        ],
    ];
    if ($modelDoc->status == MeetingMinutes::DOC_SAVE)
        array_pop($columns);

    Pjax::begin(['id' => 'mm_part_idx']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
