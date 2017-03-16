<?php

use yii\db\Migration;

class m170316_062348_gi_create_dt_enquiry_invoice_table extends Migration
{
    public function up()
    {
        $this->createTable('dt_enquiry_invoice', [
            'enquiry_id' => $this->integer()->notNull(),
            'invoice_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('dt_enquiry_invoice_pk', 'dt_enquiry_invoice', ['enquiry_id', 'invoice_id']);
    }

    public function down()
    {
        $this->dropTable('dt_enquiry_invoice');
    }
}
