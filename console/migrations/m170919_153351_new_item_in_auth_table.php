<?php
/**
 * Миграция для добавления новой роли Аудитора в базу
 */
use yii\db\Migration;

class m170919_153351_new_item_in_auth_table extends Migration
{
    public function safeUp()
    {
		$this->insert('auth_item', [
			'name' => 'auditor',
			'type' => 1,
			'description' => 'права аудитора',
		]);

		$this->insert('auth_item_child', [
			'parent' => 'auditor',
			'child' => 'user',
		]);
    }

    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'auditor']);
        $this->delete('auth_item_child', [
	        'parent' => 'auditor',
	        'child' => 'user',
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170919_153351_new_item_in_auth_table cannot be reverted.\n";

        return false;
    }
    */
}
