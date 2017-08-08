<?php
/**
 * Страница для сотрудника ит-отдела.
 * @see \backend\controllers\SiteController::actionEmployeeIt()
 */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use backend\models\User;

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
$this->registerAssetBundle('backend\assets\ModalAsset');
Modal::begin([
    'header' => '<h4 id = "modalHeader"></h4>',
    'id' => 'modal',
    'size' => 'modal-lg'
]);
echo '<div id="modalContent"></div>';
Modal::end();

?>
<div>
    <h1> Страница сотрудника It-отдела </h1>
    <div class="btn-group">
        <?= Html::a('Рабочие места', ['workplaces/index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Заявки на оборудование', ['dt-enquiries/index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Счета', ['dt-invoices/index'], ['class' => 'btn btn-default']) ?>
        <div class="btn-group">
            <?= Html::a('Устройства <span class="caret"></span>', '', [
                'class' => 'btn btn-default dropdown-toggle', 'data' => [
                    'toggle' => 'dropdown'
                ]]) ?>
            <ul class="dropdown-menu">
                <li><?= Html::a('Список устройств', ['devices/index']) ?></li>
                <li><?= Html::a('Поиск по рабочему месту и сотруднику', ['site/admin_workplace']) ?></li>
                <li><?= Html::a('Мои устройства на складе...', [
                        'devices/find-all-devices',
                        'employee_id' => User::findOne(Yii::$app->user->id)->employee_id]) ?></li>
            </ul>
        </div>
    </div>

    <?php Pjax::begin(['id' => 'pj_employee_it', 'enablePushState' => false, 'timeout' => 5000]) ?>
    <div class="row">
        <? //lg - нужно для того чтобы две таблицы не наезжали друг на друга при сужении экрана ?>
        <div class="col-lg-6">
            <?= $this->render('../dt-enquiries/to_employee_it', ['searchModel' => $search_de, 'dataProvider' => $provider_de]) ?>
        </div>
        <div class="col-lg-6">
            <?= $this->render('../dt-invoices/to_employee_it', ['searchModel' => $search_di, 'dataProvider' => $provider_di]) ?>
        </div>
    </div>

    <?php
    //таблица устройств в заявке "Устройства, требующие покупки"
    //echo $this->render('../dt-enquiry-devices/to_employee_it', ['searchModel' => $search_ded, 'dataProvider' => $provider_ded]);
    //таблица устройств в счете "Устройства, требующиие оплату"
    echo $this->render('../dt-invoice-devices/to_employee_it', ['searchModel' => $search_did, 'dataProvider' => $provider_did]);
    //таблица платежей по счету "Манипуляции с платежами"
    echo $this->render('../dt-invoices-payment/to_employee_it', ['searchModel' => $search_dip, 'dataProvider' => $provider_dip]);
    ?>
    <?php Pjax::end() ?>
</div>
