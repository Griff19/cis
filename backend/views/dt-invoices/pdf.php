<?php
/**
 * @var $model \backend\models\DtInvoices
 */
//use yii\helpers\Html;
use yii\grid\GridView;
//use yii\grid\Column;
?>

<h3> Документ счет. Тест. </h3>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'doc_date'
    ]
])?>