<?php
/**
 * Представление документа "Акт инвентаризации"
 * @see \backend\controllers\InventoryActsController::actionView()
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Images;
use backend\models\InventoryActs;

/* @var $this yii\web\View */
/* @var $model backend\models\InventoryActs */
/* @var $devProvider \yii\data\ActiveDataProvider */
/* @var $id_wp integer */

$this->title = 'Акт инвентаризации №'. $model->id;

if (isset($id_wp)) {
    $this->params['breadcrumbs'][] = ['label' => 'Рабочие места', 'url' => ['workplaces/index']];
    $this->params['breadcrumbs'][] = ['label' => 'Рабочее место №' . $model->workplace_id, 'url' => ['workplaces/view', 'id' => $model->workplace_id]];
    $this->params['breadcrumbs'][] = ['label' => 'Акты инвентаризации', 'url' => ['inventory-acts/index', 'id_wp' => $model->workplace_id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Акты инвентаризации', 'url' => ['inventory-acts/index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="modalImage" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Скан документа: </h4>
            </div>

            <div class="modal-body">
                <?php
                $key = md5('inventory_act' . $model->id);
                echo Html::img('/admin/' . Images::getLinkfile($key), ['alt' => 'Отсутствует изображение', 'width' => '100%']);
                ?>
            </div>

        </div>
    </div>
</div>
<?php
if ($model->status == InventoryActs::DOC_PRINTED) {
    echo '<blockquote>';
    echo '<p>Документ распечатан, теперь необходимо его подписать, отсканировать
            и загрузить скан для завершения цикла инвентаризации.</p>';
    echo '</blockquote>';
}
?>
<div class="inventory-acts-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
    <div class="col-xs-12 col-md-8 col-md-push-4">
    <p>
        <?php
        if (Yii::$app->user->can('sysadmin')) {
            if ($model->status == InventoryActs::DOC_NEW) {
                echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                echo ' ';
                echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Хотите удалить ' . $this->title . '?',
                        'method' => 'POST',
                    ],
                ]);
            }
            if ($model->status >= InventoryActs::DOC_SAVED) {
                echo ' ';
                echo Html::a('Печатать', ['create-pdf', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
            if ($model->status == InventoryActs::DOC_PRINTED) {
                $key = md5('inventory_act' . $model->id);
                if (Images::getLinkfile($key)) {
                    echo ' ';
                    echo Html::a('Финализировать', ['agree', 'id' => $model->id], ['class' => 'btn btn-success']);
                }
            }
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'workplace.summary',
            ['attribute' => 'owner_employee_id',
                'value' => $model->ownerEmployee->snp,
            ],
            ['attribute' => 'exec_employee_id',
                'value' => $model->execEmployee->snp,
            ],
            'act_date',
            'curr_date:datetime',
            'status',
            'comm',
        ],
    ]) ?>
    </div>
    <div class="col-xs-12 col-md-4 col-md-pull-8">
        <div class="img-thumbnail" style="margin-top: 20px">
            <?php
            $key = md5('inventory_act' . $model->id);
            echo Html::img('/admin/' . Images::getLinkfile($key), ['width' => '300px', 'alt' => 'Отсутствует изображение',
                    'data-toggle' => 'modal', 'data-target' => '#modalImage']) . '<br>';
            if (Yii::$app->user->can('admin'))
                echo Html::a('Изменить', ['images/index', 'owner' => $key, 'owner_id' => $model->id, 'target' => 'inventory-acts/view']);
            ?>
        </div>
    </div>
    </div>
    <div class="row">
    <?= $this->render('devices', ['modelDoc' => $model, 'dataProvider' => $devProvider, 'id_wp' => $model->workplace_id])?>
    <?php
    if ($model->status == 0) {
        echo $this->render('../inventory-acts-tb/index', [
            'modelDoc' => $model, 'dataProvider' => $iatProvider, 'searchModel' => $iatSearch]);

        echo Html::a('<span class="glyphicon glyphicon-floppy-disk" style="font-size: large"></span> Все готово! Сохранить.', [
            'save', 'id' => $model->id],
            ['class' => 'btn btn-success', 'style' => 'float: right']);
    }
    ?>
    </div>
</div>
