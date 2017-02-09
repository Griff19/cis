<?php


use yii\grid\GridView;


/* @var $dataProvider yii\data\ActiveDataProvider */


?>

<div class="mmdecision-index" >

    <h4> Принятые решения </h4>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            ['attribute' => 'content',
				'header' => 'Содержание решения',
				'value' => 'content',
			],
            ['attribute' => 'due_date',
				'header' => 'Дата исполнения',
				'value' => 'due_date',
				'format' => 'date'
			]
        ],
    ]); ?>

</div>
