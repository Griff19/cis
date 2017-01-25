<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DPartnerContacts */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => $model->partner->name_partner, 'url' => ['d-partners/view', 'id' => $model->partner_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dpartner-contacts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить этот контакт?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'job_title',
            'phone1',
			'phone2',
            'add_number',
			'email:email',
			'icq',
            //'partner_id',
            'title',
        ],
    ]) ?>

</div>
