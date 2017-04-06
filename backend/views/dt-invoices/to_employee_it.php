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

    <h3> Текущие счета:
        <?= Html::a('Ведомость на согласование', ['dt-invoices/pdf', 'id' => 0, 'mode' => 2], ['class' => 'btn btn-default', 'data-pjax' => 0]) ?>
        <?= Html::a('Ведомость на оплату', ['dt-invoices/pdf', 'id' => 0, 'mode' => 1], ['class' => 'btn btn-default', 'data-pjax' => 0]) ?>
    </h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['attribute' => 'doc_number',
                'value' => function ($model) {
                    /** @var $model DtInvoices */
                    return Html::a($model->summary,
                        ['dt-invoices/view', 'id' => $model->id], ['data-pjax' => 0]);
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
                        $a = Html::a('Печать', ['dt-invoices/pdf', 'id' => $model->id],
                            ['data-pjax' => 0]);
                        $a .= ' ';
                        $a .= Html::a('Согласован...', 'javascript:', [
                            'id' => 'linkModal',
                            'data-target' => '/admin/dt-invoices-payment/create?id='
                                . $model->id
                                . '&is_modal=true',
                            'data-header' => 'Фиксация согласованного платежа',
                            'data-pjax' => 1
                        ]);
                    }
                    if ($model->status == DtInvoices::DOC_AWAITING_PAYMENT) {
                        $a = Html::a('Отправить', ['dt-invoices/set-status',
                            'id' => $model->id,
                            'status' => DtInvoices::DOC_SENT_FOR_PAYMENT
                        ],
                            ['class' => 'btn btn-primary btn-sm',
                                'title' => 'Отправить бухгалтеру на оплату',
                            ]
                        );
                    }
                    if ($model->status == DtInvoices::DOC_SENT_FOR_PAYMENT) {
                        $a = Html::a('Подтвердить', ['dt-invoices/set-status',
                            'id' => $model->id,
                            'status' => DtInvoices::DOC_SAVE,
                        ],
                            ['class' => 'btn btn-success btn-sm', 'title' => 'Подтвердить прошедшую оплату']
                        );
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
