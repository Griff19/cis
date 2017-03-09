<?php

use yii\db\Migration;

class m170309_035508_gi_new_meeting_minutes_status_field extends Migration
{
    public function up()
    {
        $this->addColumn('meeting_minutes', 'status', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('meeting_minutes', 'status');
    }
}
