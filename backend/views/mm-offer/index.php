<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\MmOfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Mm Offers';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mmoffer-index" style="margin-top: 40px">

    <h3> Выдвинутые предложения </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Mm Offer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'mmoffer_idx']); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'mm_id',
            'content:ntext',

            ['class' => 'yii\grid\ActionColumn',
				'controller' => 'mm-offer',
				'template' => '{delete}'
			],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
