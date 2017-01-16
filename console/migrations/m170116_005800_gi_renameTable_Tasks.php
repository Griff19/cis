<?php

use yii\db\Migration;

class m170116_005800_gi_renameTable_Tasks extends Migration
{
    public function up()
    {
        $this->renameTable('tasks', 'message');
    }

    public function down()
    {
        $this->renameTable('message', 'tasks');
    }
}
