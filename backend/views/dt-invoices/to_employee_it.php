<?php
/**
 * Список активных документов "Счет"
 * Встраивается на страницу сотрудника it-отдела (site/it_index)
 */

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtInvoicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-index">

    <h3> Текущие счета </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'doc_number',
            'doc_date:date',
            //'d_partners_id',
            ['attribute' => 'partner.name_partner', 'label' => 'Контрагент'],
            'summ',
			['attribute' => 'summPay', 'label' => 'Оплачено'],
//            'delivery_type',
            ['attribute' => 'status',
                'value' => 'statusString',
                'filter' => \backend\models\DtInvoices::arrStatusString()
            ],
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
