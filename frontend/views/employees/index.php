<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\Column;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= $id_wp == 0 ? '' : 'Выберите ответсвенного сотрудника или создайте нового:' ?>
        <?= Html::a('Создать нового', ['create', 'id_wp' => $id_wp], ['class' => 'btn btn-success']) ?>

        <?php if(Yii::$app->user->can('admin')) {
            //echo Html::a('Загрузить', ['readfile'], ['class' => 'btn btn-success']);
            echo Html::a('Загрузить', ['uploadform'], ['class' => 'btn btn-success']);
        }?>
    </p>
    <?php
        $cols = [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'id',
                'value' => 'id',
                'options' => ['style' => 'width:10px']
            ],
            'snp',
            //'surname',
            //'name',
            //'patronymic',
            'job_title',
            // 'employee_number',
            // 'unique_1c_number',
            //'branch_id',
            [
                'attribute' => 'branch_id',
                'value' => 'branch.branch_title'
            ],
            ['class' => Column::className(),
                'content' => function($model) use ($id_wp){
                    return Html::a('', ['wpowners/adduser', 'id_empl' => $model->id, 'id_wp' => $id_wp],
                    ['class' => 'ok',
                        'title' => 'Выбрать сотрудника...']);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ];
        if ($mode !== 'select') {
            unset($cols[5]);
        } else {
            unset($cols[6]);
        }
    ?>

    <?php
        //выводим алфавит для быстрого фильтра по первой букве ФИО
        echo '<ul class="pagination">';
        foreach (range(chr(0xC0), chr(0xDF)) as $a) {
            $a = iconv('CP1251', 'UTF-8', $a);
            echo '<li><a href = "index?EmployeesSearch[snp]='.$a.'&r=employees/index&mode='.$mode.'&id_wp='.$id_wp.'&pag=0">'.$a.'</a></li>';
        }
        echo '</ul>';
    ?>
    <?php
    //отключаем пагинацию при быстром фильтре
    if($pag == 0){ $dataProvider->pagination = false; } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'prevPageLabel' => 'Назад',
            'nextPageLabel' => 'Далее',
            'maxButtonCount' => 20
        ],
        'columns' => $cols,
    ]); ?>

</div>
