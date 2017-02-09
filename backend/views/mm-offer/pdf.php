<?php

use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="mmoffer-index" >

    <h4> Выдвинутые предложения </h4>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'content',
				'header' => 'Содержание предложений',
				'value' => 'content'
			]
        ],
    ]); ?>

</div>
