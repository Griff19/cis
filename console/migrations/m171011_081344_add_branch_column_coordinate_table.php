<?php

use yii\db\Migration;

class m171011_081344_add_branch_column_coordinate_table extends Migration
{
    public function safeUp()
    {
		$this->addColumn('coordinate', 'branch_id', $this->integer()->comment('код подразделения'));
    }

    public function safeDown()
    {
		$this->dropColumn('coordinate', 'branch_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171011_081344_add_branch_column_coordinate_table cannot be reverted.\n";

        return false;
    }
    */
}
