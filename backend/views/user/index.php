<?php

use yii\helpers\Html;
//use yii\widgets\ListView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
        &#8195;
        <span class="glyphicon glyphicon-info-sign"></span> У сотрудника, для которого создается пользователь, должен быть указан email.
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'employee_id',
                'value' => 'employee.snp',
                'label' => 'Ф.И.О.'
            ],
            'email',
            'created_at:date',
            ['class' => '\yii\grid\ActionColumn']
        ]
    ])?>

</div>
