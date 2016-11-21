<?php

use yii\db\Migration;

class m161117_184626_gi_newFieldToDeviceType extends Migration
{
    public function up()
    {
        $this->addColumn('device_type', 'usr_sort', $this->integer() . ' DEFAULT NULL');
        $this->addCommentOnColumn('device_type', 'usr_sort', 'поле для пользовательской сортировки');
    }

    public function down()
    {
        $this->dropColumn('device_type', 'usr_sort');
    }
}
