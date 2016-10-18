<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $mainModel \backend\models\DPartners */
/* @var $searchModel backend\models\DPartnerContractsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dpartner-contracts-index">

    <h1>Заключенные Контракты:</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить контракт', ['d-partner-contracts/create', 'partner_id' => $mainModel->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'contract_number',
            'contract_date',
            'partner_id',
            'title',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'controller' => 'd-partner-contracts'
            ],
        ],
    ]); ?>
</div>
