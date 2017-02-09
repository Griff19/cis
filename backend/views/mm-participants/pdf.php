<?php
/**
 * PDF-представление "Участники встречи"
 * Встраивается в PDF-представление "Протокол встречи" (meeting-minutes\pdf.php)
 * Выводит список сотрудников, учавствовавших во встрече
 */

use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="mm-participants-index">

    <h4> Участники встречи: </h4>

	<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
		'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			['attribute' => 'employee_name',
				'value' => 'employee.snp'
			],
			['class' => 'yii\grid\Column',
				'header' => 'Подпись',

			]
        ],
    ]); ?>

</div>
