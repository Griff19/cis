<?php
/**
 * Представление "Участники встречи"
 * Встраивается в представление "Протокол встречи" (meeting-minutes\view.php)
 * Выводит список сотрудников, учавствующих во встрече
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MmParticipantsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Участники';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-participants-index">

    <h3> Участники встречи: </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Добавить участника', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'mm_part_idx']); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'mm_id',
            //'employee_id',
			['attribute' => 'employee_name',
				'value' => 'employee.snp'
			],
            ['class' => 'yii\grid\ActionColumn',
				'controller' => 'mm-participants',
				'template' => '{delete}'
			],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
