<?php

use yii\db\Migration;

class m171006_165446_add_field_to_coordinate_table extends Migration
{
    public function safeUp()
    {
		$this->addColumn('coordinate', 'content', $this->char(32));
		$this->addCommentOnColumn('coordinate', 'content', 'содержимое, отображаемое на метке');

    }

    public function safeDown()
    {
        $this->dropColumn('coordinate', 'content');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171006_165446_add_field_to_coordinate_table cannot be reverted.\n";

        return false;
    }
    */
}
