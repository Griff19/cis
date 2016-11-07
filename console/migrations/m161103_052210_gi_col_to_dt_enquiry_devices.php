<?php

use yii\db\Migration;
use yii\db\pgsql\Schema;

class m161103_052210_gi_col_to_dt_enquiry_devices extends Migration
{
    public function up()
    {
        $this->addColumn('dt_enquiry_devices', 'dt_inv_id', Schema::TYPE_INTEGER);
        $this->addCommentOnColumn('dt_enquiry_devices', 'dt_inv_id', 'идентификатор документа Счет');

    }

    public function down()
    {
        $this->dropColumn('dt_enquiry_devices', 'dt_inv_id');
    }

}
