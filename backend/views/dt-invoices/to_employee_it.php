<?php
/**
 * Список активных документов "Счет"
 * Встраивается на страницу сотрудника it-отдела (site/it_index)
 */

use yii\grid\GridView;
use yii\helpers\Html;
use backend\models\DtInvoices;

/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\DtInvoicesSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

?>
<div class="dt-invoices-index">

    <h3> Текущие счета: </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'doc_number',
                'value' => function ($model) {
                    /** @var $model DtInvoices */
                    return Html::a($model->summary,
                        ['dt-invoices/view', 'id' => $model->id], ['data-method' => 'get']);
                },
                'format' => 'raw',
            ],
            ['attribute' => 'partner.name_partner', 'label' => 'Контрагент'],
            'summ',
			['attribute' => 'summPay', 'label' => 'Оплачено'],
            ['attribute' => 'status',
                'value' => function ($model) {
                    /** @var $model \backend\models\DtInvoices */
                    $str = '';
                    if ($model->summ <= $model->summPay && $model->status != DtInvoices::DOC_CLOSED)
                        $str = Html::a('Закрыть', ['dt-invoices/save', 'id' => $model->id, 'mode' => 1],
                            ['title' => 'Провести закрытие счета']);
                    return $model->statusString . ' ' .$str;
                },
                'format' => 'raw',
                'filter' => DtInvoices::arrStatusString()
            ],
        ],
    ]); ?>
</div>
