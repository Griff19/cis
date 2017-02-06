<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MmAgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Mm Agendas';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mm-agenda-index">

    <h3> Повестка встречи </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Mm Agenda', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'mmagenda_idx']); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'mm_id',
            'content:ntext',

            ['class' => 'yii\grid\ActionColumn',
				'controller' => 'mm-agenda',
				'template' => '{delete}'
			],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
