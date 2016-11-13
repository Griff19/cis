<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelDoc \backend\models\DtInvoices */
/* @var $searchModel backend\models\DtInvoicesPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dt-invoices-payment-index">

    <h1> История платежей: </h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Внести оплату', ['dt-invoices-payment/create', 'id' => $modelDoc->id ], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'dt_invoices_id',
            'agreed_date',
            'summ',
            'employee.snp',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'dt-invoices-payment',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
