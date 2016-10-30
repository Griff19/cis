<?php

use yii\db\Migration;
use yii\db\pgsql\Schema;

class m161030_193919_gi_newField extends Migration
{
    public function up()
    {
        $this->addColumn('dt_invoice_devices', 'dt_enquiry_devices_id', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('dt_invoice_devices', 'dt_enquiry_devices_id');
    }
    
}
