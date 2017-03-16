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
                        ['dt-invoices/view', 'id' => $model->id], ['data-method' => 'post']);
                },
                'format' => 'raw',
            ],
            ['attribute' => 'partner.name_partner', 'label' => 'Контрагент'],
            'summ',
            ['attribute' => 'summPay', 'label' => 'Оплачено'],
            ['attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusString;
                },
                'filter' => DtInvoices::arrStatusString(),
                'format' => 'raw',
            ],
            ['class' => '\yii\grid\Column',
                'header' => 'Действия',
                'content' => function ($model) {
                    /** @var $model \backend\models\DtInvoices */
                    $a = '';
                    if ($model->status == DtInvoices::DOC_WAITING_AGREE) {
                        $a = Html::a('Печать', ['dt-invoices/create-pdf', 'id' => $model->id]);
                        $a .= ' ';
                        $a .= Html::a('Согласован...', '#', [
                            'id' => 'linkModal',
                            'data-target' => '/admin/dt-invoices-payment/create?id='
                                . $model->id
                                . '&is_modal=true',
                            'data-header' => 'Фиксация согласованного платежа']);
                    }
                    if ($model->summ <= $model->summPay && $model->status == DtInvoices::DOC_SAVE)
                        $a = Html::a('Закрыть', ['dt-invoices/save', 'id' => $model->id, 'mode' => 1],
                            ['title' => 'Провести закрытие счета']);

                    return $a;
                }
            ],
        ],
    ]); ?>
</div>
