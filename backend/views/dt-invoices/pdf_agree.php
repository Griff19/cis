<?php
/**
 * Представление "Ведомость на согласование"
 * @var $mode integer Режим отображения 1 - согласование, 0 - оплата
 */

?>
<h1>Ведомость на <?= $mode == 0 ? 'оплату' : 'согласование' ?></h1>

<?= \yii\grid\GridView::widget([
    'tableOptions' => ['class' => 'table table-striped table-condensed', 'style' => 'font-size: 12px'],
    'dataProvider' => $dt_invoices,
    'columns' => [
        ['attribute' => 'doc_number', 'header' => 'Док. №'],
        ['attribute' => 'doc_date', 'header' => 'Док. Дата'],
        ['attribute' => 'd_partners_name', 'header' => 'Контрагент', 'value' => 'partner.brand'],
        ['attribute' => 'summ', 'header' => 'Сумма'],
        'statusString:raw'
    ]
])?>
