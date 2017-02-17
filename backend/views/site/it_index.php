<?php
/**
 * Страница для сотрудника ит-отдела. Генерируется actionEmployeeIt() контроллера SiteController
 */

use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var $provider_de \yii\data\ActiveDataProvider
 * @var $search_de \backend\models\DtEnquiriesSearch
 * @var $provider_di \yii\data\ActiveDataProvider
 * @var $search_di \backend\models\DtInvoicesSearch
 * @var $provider_did \yii\data\ActiveDataProvider
 * @var $search_did \backend\models\DtInvoiceDevicesSearch
 * @var $provider_ded \yii\data\ActiveDataProvider
 * @var $search_ded \backend\models\DtEnquiryDevicesSearch
 * @var $provider_dip \yii\data\ActiveDataProvider
 * @var $search_dip \backend\models\DtInvoicesPaymentSearch
 */

?>
<div>
	<h1> Страница сотрудника It-отдела </h1>
	<div class="row">
		<div class="col-sm-3">
			<?=  Html::a('Рабочие места', ['workplaces/index'], ['class'=>'btn btn-primary'])?>
			<p>Список рабочих мест.</p>
		</div>
		<div class="col-sm-3">
			<?= Html::a('Заявки на оборудование',['dt-enquiries/index'],['class'=>'btn btn-warning'])?>
			<p>Заявки на оборудование</p>
		</div>
	</div>
    <?php Pjax::begin(['enablePushState' => false])?>
	<div class="row">
	<?//lg - нужно для того чтобы две таблицы не наезжали друг на друга при сужении экрана ?>
		<div class="col-lg-6">
			<?= $this->render('../dt-enquiries/to_employee_it', ['searchModel' => $search_de, 'dataProvider' => $provider_de]) ?>
		</div>
		<div class="col-lg-6">
			<?= $this->render('../dt-invoices/to_employee_it', ['searchModel' => $search_di, 'dataProvider' => $provider_di])?>
		</div>
	</div>

	<?php
	//таблица устройств в заявке "Устройства, требующие покупки"
    echo $this->render('../dt-enquiry-devices/to_employee_it', ['searchModel' => $search_ded, 'dataProvider' => $provider_ded]);
	//таблица устройств в счете "Устройства, требующиие оплату"
	echo $this->render('../dt-invoice-devices/to_employee_it', ['searchModel' => $search_did, 'dataProvider' => $provider_did]);
	//таблица платежей по счету "Манипуляции с платежами"
    echo $this->render('../dt-invoices-payment/to_employee_it', ['searchModel' => $search_dip, 'dataProvider' => $provider_dip]);
	?>
    <?php Pjax::end()?>
</div>
