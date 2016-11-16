<?php
/**
 * Создаем поле "статус" для документа счет
 * нужно для отслеживания состояния (новый, сохранен)
 */
use yii\db\Migration;
use yii\db\pgsql\Schema;

class m161116_053133_gi_newFieldToInvoices extends Migration
{
    public function up()
    {
        $this->addColumn('dt_invoices', 'status', Schema::TYPE_INTEGER . ' DEFAULT 1');
        $this->addCommentOnColumn('dt_invoices', 'status', 'статус документа');
    }

    public function down()
    {
        $this->dropColumn('dt_invoices', 'status');
    }
}
