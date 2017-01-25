<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $mainModel \backend\models\DPartners */
/* @var $searchModel backend\models\DPartnerContactsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dpartner-contacts-index">

    <h1>Контакты:</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить контакт', ['d-partner-contacts/create', 'partner_id' => $mainModel->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'full_name',
            'job_title',
            'phone1',
            'phone2',
			'add_number',

            ['class' => 'yii\grid\ActionColumn',
                //'template' => '{update}{delete}',
                'controller' => 'd-partner-contacts'
            ],
        ],
    ]); ?>
</div>
