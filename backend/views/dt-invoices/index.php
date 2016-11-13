<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DtInvoicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета' ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-invoices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Счет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'doc_number',
            'doc_date',
            //'d_partners_id',
            ['attribute' => 'partner.name_partner',
                'header' => 'Контрагент'
            ],
            'summ',
            'summPay',
            'delivery_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
