<?php
/**
 * Основное представление документа "Протокол встреч"
 * @see MeetingMinutesController::actionView()
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\MeetingMinutes;

/* @var $this yii\web\View */
/* @var $model backend\models\MeetingMinutes */

$this->title = 'Протокол встречи №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Протоколы встреч', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-minutes-view">
    <div class="row">
        <div class="col-lg-6">
            <h1><?= Html::encode($this->title) ?>
                <?php
                if ($model->status == MeetingMinutes::DOC_NEW) {
                    echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Уверенны что хотите удалить этот документ?',
                            'method' => 'post',
                        ],
                    ]);
                }
                ?>
                <?= Html::a('<span class="glyphicon glyphicon-print"></span> <b>PDF</b>', ['meeting-minutes/pdf', 'id' => $model->id], [
                    'class' => 'btn btn-default',
                    'style' => 'padding: 3px 6px',
                    'title' => 'Открыть PDF'
                ]); ?>
            </h1>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'doc_num',
                    'doc_date:date',
                ],
            ]) ?>

            <?= $this->render('../mm-agenda/index', ['modelDoc' => $model, 'dataProvider' => $mma_provider, 'searchModel' => $mma_search]) ?>
            <?= $model->status == MeetingMinutes::DOC_NEW ? $this->render('../mm-agenda/_form', ['model' => $mma_model]) : '' ?>
        </div>
        <div class="col-lg-6">
            <?= $this->render('../mm-participants/index', ['modelDoc' => $model, 'dataProvider' => $mmp_provider, 'searchModel' => $mmp_search]) ?>
            <?= $model->status == MeetingMinutes::DOC_NEW ? $this->render('../mm-participants/_form', ['model' => $mmp_model]) : '' ?>
        </div>
    </div>


    <?= $this->render('../mm-offer/index', ['modelDoc' => $model, 'dataProvider' => $mmo_provider, 'searchModel' => $mmo_search]) ?>
    <?= $model->status == MeetingMinutes::DOC_NEW ? $this->render('../mm-offer/_form', ['model' => $mmo_model]) : '' ?>

    <?= $this->render('../mm-decision/index', ['modelDoc' => $model, 'dataProvider' => $mmd_provider, 'searchModel' => $mmd_search]) ?>
    <?php if ($model->status == MeetingMinutes::DOC_NEW) {
        echo $this->render('../mm-decision/_form', ['model' => $mmd_model]);
        echo Html::a('Сохранить документ', ['save', 'id' => $model->id], ['class' => 'btn btn-primary']);
    } ?>


</div>

<?php
$this->registerJsFile('/admin/js/check_keys.js');
?>
