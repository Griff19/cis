<?php

use yii\db\Migration;

class m180605_074407_set_rbac_auditor extends Migration
{
    public function safeUp()
    {
        $this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'auditor']);
        $this->delete('auth_item_child', ['parent' => 'auditor', 'child' => 'user']);
        
        $this->insert('auth_item_child', ['parent' => 'auditor', 'child' => 'it']);
    }

    public function safeDown()
    {
        $this->delete('auth_item_child', ['parent' => 'auditor', 'child' => 'it']);
        
        $this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'auditor']);
        $this->insert('auth_item_child', ['parent' => 'auditor', 'child' => 'user']);
    }
}
