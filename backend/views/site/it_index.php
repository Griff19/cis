<?php
/**
 * Страница для сотрудника ит-отдела. Генерируется actionEmployeeIt() контроллера SiteController
 */

use yii\helpers\Html;

/**
 * @var $provider_did \yii\data\ActiveDataProvider
 * @var $search_did \backend\models\DtInvoiceDevicesSearch
 * @var $provider_ded \yii\data\ActiveDataProvider
 * @var $search_ded \backend\models\DtEnquiryDevicesSearch
 */

?>
<div>
	<h1> Страница сотрудника It-отдела </h1>
	<div class="row">

		<div class="col-sm-3">
			<?=  Html::a('Рабочие места', ['workplaces/index'], ['class'=>'btn btn-primary'])?>
			<p>Список рабочих мест.</p>
		</div>
	</div>
</div>
<?= $this->render('../dt-enquiry-devices/to_employee_it', ['searchModel' => $search_ded,'dataProvider' => $provider_ded]) ?>
<?= $this->render('../dt-invoice-devices/to_employee_it', ['searchModel' => $search_did,'dataProvider' => $provider_did]) ?>