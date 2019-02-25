<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CellnumbersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мобильные номера';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cell-numbers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить новый номер', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Загрузить файл', ['uploadform'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Скачать с FTP', ['dwnftp'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Открыть счета за услуги связи', ['/phone-bill'], ['class' => 'btn btn-primary'])?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cell_number',
            [
                'attribute' => 'employee_id',
                'value' => 'employee.snp'
            ],
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
