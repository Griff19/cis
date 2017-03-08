<?php
/**
 * Список согласованных платежей по счету
 * встраивается в представление dt-invoices\view
 */
use backend\models\DtInvoices;
use backend\models\DtInvoicesPayment;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoicesPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-payment-index">

    <h3> История платежей: </h3>
    <p>
        <?= $modelDoc->status == DtInvoices::DOC_NEW ? Html::a('Добавить платеж', ['dt-invoices-payment/create', 'id' => $modelDoc->id ], ['class' => 'btn btn-success']) : ''; ?>
    </p>
    <?php
        $cols = [
            ['class' => 'yii\grid\SerialColumn'],
            'agreed_date:date',
            'summ',
            ['attribute' => 'employee.snp', 'label' => 'Согласовавший'],
            ['attribute' => 'status',
                'value' => function ($model) {
                    $status = $model->statusString;

                    if ($model->status == DtInvoicesPayment::PAY_AGREED) {
                        $set = DtInvoicesPayment::PAY_REFER;
                        $str = '(Отправить)';
                        $title = 'Отправить бухгалтеру на оплату';
                    }elseif ($model->status == DtInvoicesPayment::PAY_REFER) {
                        $set = DtInvoicesPayment::PAY_OK;
                        $str = '(Подтвердить)';
                        $title = 'Подтвердить прошедшую оплату';
                    }
                    return $status . (empty($str) ? '' : ' '. Html::a($str, ['dt-invoices/set-status-payment',
                                'id' => $model->id,
                                'status' => $set],
                                ['title' => $title]));
                },
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
            ],
        ];
        //если документ закрыт то убираем колонку действий
        if ($modelDoc->status == DtInvoices::DOC_CLOSED)
            array_pop($cols);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            return ['class' => $model->status != DtInvoicesPayment::PAY_AGREED ? : 'success'];
        },
        'columns' => $cols,
    ]); ?>

</div>
