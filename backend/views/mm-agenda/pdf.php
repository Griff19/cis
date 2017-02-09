<?php
/**
 * PDF-представление "Повестка встречи"
 * Встраивается в PDF-представление "Протокол встречи" (meeting-minutes/pdf.php)
 * Выводит пункты повестки встречи
 */
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="mm-agenda-index">

    <h4> Повестка встречи </h4>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			['attribute' => 'content',
				'header' => 'Содержание повестки',
				'value' => 'content'
        	]
    	]
	]); ?>

</div>
