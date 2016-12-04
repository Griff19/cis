<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use backend\models\Images;
use backend\models\DtDefsheets;


/* @var $this yii\web\View */
/* @var $model backend\models\DtDefsheets */
$this->registerAssetBundle('backend\assets\ModalAsset');

$this->title = 'Акт списания №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Акты списания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//Модальное окно для правки сотрудника
Modal::begin([
    'header' => '<h4 id = modalHeader></h4>',
    'id' => 'modal',
    'size' => 'modal-md'
]);
    echo '<div id = modalContent></div>';
Modal::end();
//модальное окно для вывода изображения
Modal::begin([
    'header' => '<p>Скан документа:</p>',
    'id' => 'modalImg',
    'size' => 'modal-lg'
]);
    $key = md5('dt-defsheets' . $model->id);
    echo Html::img('/admin/' . Images::getLinkfile($key), ['style' => 'width: 100%', 'alt' => 'Отсутствует изображение']);
Modal::end();

?>
<div class="dt-defsheets-view">
    <div class="row">
        <div class="col-lg-6">
            <?php if ($model->status == DtDefsheets::STATUS_SAVED){?>
                <blockquote>
                    Документ сохранен и ожидает подтверждения. Перед этим необходимо загрузить скан подписанного документа.
                </blockquote>
            <?php }?>
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                <?php
                if ($model->status == DtDefsheets::STATUS_CONFIRM)
                    echo Html::a('Оформить заявку', '', ['class' => 'btn btn-primary']);
                if ($model->status == DtDefsheets::STATUS_SAVED){
                    echo Html::a('Печать', ['create-pdf', 'id' => $model->id], ['class' => 'btn btn-success']);
                    echo Html::a('Удалить документ', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'style' => 'float: right',
                    'data' => [
                        'confirm' => 'Удалить Акт списания №' . $model->id . '?',
                        'method' => 'post',
                    ]
                    ]);
                }?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'date_create:datetime',
                    'date_confirm',
                    'status',
                    //'employee_id',
                    ['attribute' => 'employee_id',
                        'value' => $model->status >= DtDefsheets::STATUS_SAVED ?
                            $model->employee->snp :
                            Html::a($model->employee_id ? $model->employee->snp : 'Указать Сотрудника', '#',
                                ['class' => $model->employee_id ? '' : 'btn btn-primary',
                                    'id' => 'linkModal',
                                    'data-target' => 'update?id=' . $model->id,
                                    'data-header' => 'Укажите сотрудника'
                                ]),
                        'format' => 'raw'
                    ]
                ],
            ]) ?>
        </div>
        <div class="col-lg-6">
            <div class="img-thumbnail img-block" style="margin-top: 20px; height: 350px">
                <?php
                $key = md5('dt-defsheets' . $model->id);
                echo Html::a('Добавить/Изменить скан', ['images/index',
                    'target' => 'dt-defsheets/view',
                    'owner' => $key,
                    'owner_id' => $model->id]);
                //echo Html::a('Удалить скан', '', ['class' => 'btn btn-danger', 'style' => 'float: right']);
                ?>
                <br>
                <?php
                $img = Html::img('/admin/' . Images::getLinkfile($key), ['class' => 'img-responsive', 'alt' => 'Отсутствует изображение']);
                echo Html::a($img, '#', ['data-toggle' => 'modal', 'data-target' => '#modalImg']);
                ?>
            </div>
        </div>
    </div>
    <?= $this->render('../dt-defsheet-devices/index', ['modelDoc' => $model, 'dataProvider' => $ddsDeviceProvider, 'searchModel' => $ddsDeviceSearch]) ?>

    <br>
    <?php
    if ($model->status == 0)
        echo Html::a('Сохранить', ['save', 'id' => $model->id], ['class' => 'btn btn-success']);
    if ($model->status == 1)
        echo Html::a('Подтвердить', ['agree', 'id' => $model->id], ['class' => 'btn btn-success']);
    ?>

</div>
