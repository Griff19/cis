<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\InventoryActs;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InventoryActsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акты инвентаризации';

if ($id_wp) {
    $this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['workplaces/index']];
    $this->params['breadcrumbs'][] = ['label' => 'Рабочее место №' . $id_wp, 'url' => ['workplaces/view', 'id' => $id_wp]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-acts-index">

    <h1><?= Html::encode($this->title . ($id_wp ? ' по Рабочему месту №' . $id_wp : '')) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Акт Инвентаризации', ['create' . ($id_wp ? '?id_wp=' . $id_wp : '')], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            $res = '';
            if ($model->status == InventoryActs::DOC_SAVED || $model->status == InventoryActs::DOC_PRINTED)
                $res = ['class' => 'warning'];
            elseif ($model->status == InventoryActs::DOC_AGREE)
                $res = ['class' => 'success'];
            return $res;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'workplace_id',
            //'owner_employee_id',
            ['attribute' => 'owner_employee_id',
                'value' => function ($model) {
                    return $model->ownerEmployee->snp;
                }
            ],
            //'exec_employee_id',
            ['attribute' => 'exec_employee_id',
                'value' => function ($model) {
                    return $model->execEmployee->snp;
                }
            ],
            'act_date',
            ['class' => 'yii\grid\Column',
                'content' => function ($model) use ($id_wp){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',['delete', 'id' => $model->id, 'id_wp' => $id_wp],['data-method' => 'POST']);
                }
            ],
            ['class' => 'yii\grid\Column',
                'content' => function ($model) use ($id_wp){
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view', 'id' => $model->id, 'id_wp' => $id_wp]);
                }
            ]
        ],
    ]); ?>
</div>
