<?php

use yii\db\Migration;

/**
 * Class m180919_061405_set_rbac_sysadmin
 */
class m180919_061405_set_rbac_sysadmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('auth_item', ['name' => 'sysadmin', 'type' => '1', 'description' => 'права системного администратора']);
        
        $this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'it']);
        $this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'sysadmin']);
        $this->insert('auth_item_child', ['parent' => 'sysadmin', 'child' => 'it']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'sysadmin']);
    
        $this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'sysadmin']);
        $this->delete('auth_item_child', ['parent' => 'sysadmin', 'child' => 'it']);
        $this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'it']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180919_061405_set_rbac_sysadmin cannot be reverted.\n";

        return false;
    }
    */
}
