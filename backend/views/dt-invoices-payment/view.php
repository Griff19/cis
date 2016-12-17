<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use backend\models\Images;

/* @var $this yii\web\View */
/* @var $model backend\models\DtInvoicesPayment */

$this->title = $model->summ. 'р. от ' .$model->agreed_date;
$this->params['breadcrumbs'][] = ['label' => 'Счет №' . $model->dt_invoices_id, 'url' => ['dt-invoices/view', 'id' => $model->dt_invoices_id]];
$this->params['breadcrumbs'][] = $this->title;
//окно для вывода увеличенного изображения
Modal::begin([
    'id' => 'modalImg',
    'size' => 'modal-lg'
]);
$key = md5('dt-invoices' . $model->id);
echo Html::img('/admin/' . Images::getLinkfile($key), ['style' => 'width: 100%', 'alt' => 'Отсутствует изображение']);
Modal::end();

?>
<div class="dt-invoices-payment-view">


    <div class="row">
        <div class="col-sm-6">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Хотите удалить это платеж?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'dt_invoices_id',
                'agreed_date',
                'summ',
                'employee.snp',
                'statusString'
            ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <div class="img-thumbnail img-block" style="margin-top: 20px; height: 350px">
                <?php
                $key = md5('dt-invoices' . $model->id);
                echo Html::a('Добавить/Изменить скан', ['images/index',
                    'target' => 'dt-invoices/view',
                    'owner' => $key,
                    'owner_id' => $model->id]);
                //echo Html::a('Удалить скан', '', ['class' => 'btn btn-danger', 'style' => 'float: right']);
                ?>
                <br>
                <?php $img = Html::img('/admin/' . Images::getLinkfile($key), ['class' => 'img-responsive', 'alt' => 'Отсутствует изображение']);
                echo Html::a($img, '#', ['data-toggle' => 'modal', 'data-target' => '#modalImg']);
                ?>
            </div>
        </div>
    </div>
    <?= Html::a('Платеж согласован', '', ['class' =>  'btn btn-success'])?>
</div>
