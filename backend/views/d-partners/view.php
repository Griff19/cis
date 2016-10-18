<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartners */

$this->title = $model->name_partner;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpartners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверенны что хотите удалить этого контрагента?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_partner',
            'type_partner',
            'brand',
            'inn',
        ],
    ]) ?>

    <?= $this->render('..\d-partner-contacts\index', ['mainModel' => $model, 'dataProvider' => $dp_cont_provider, 'searchModel' => $dp_cont_search]) ?>
    <?= $this->render('..\d-partner-contracts\index', ['mainModel' => $model, 'dataProvider' => $dp_contr_provider, 'searchModel' => $dp_contr_search]) ?>


</div>
