<?php
/**
 * @var $model \backend\models\DtEnquiries
 */
?>
<h1> Согласование оплаты Заявки </h1>
<?= \yii\grid\GridView::widget([
	'dataProvider' => $provider
]) ?>