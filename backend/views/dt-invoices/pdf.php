<?php
/**
 * Представление pdf-версии документа "Счет" (dt-invoices/create-pdf)
 */
use yii\widgets\DetailView;

/**
 * @var $model \backend\models\DtInvoices
 */

?>

<h3> Документ счет. Тест. </h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'doc_date'
    ]
])?>