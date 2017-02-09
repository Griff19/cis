<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MmDecisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Mm Decisions';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mmdecision-index" style="margin-top: 40px">

    <h3> Принятые решения </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Mm Decision', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'mmdecision_idx']); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'mm_id',
            'content:ntext',
            'due_date:date',

            ['class' => 'yii\grid\ActionColumn',
				'controller' => 'mm-decision',
				'template' => '{delete}'
			],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
