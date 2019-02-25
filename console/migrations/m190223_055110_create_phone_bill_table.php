<?php

use yii\db\Migration;

/**
 * Handles the creation of table `phone_bill`.
 */
class m190223_055110_create_phone_bill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('phone_bill', [
            'id' => $this->primaryKey(),
            'number' => $this->string(12)->notNull()->comment('Номер телефона'),
            'date' => $this->date()->notNull()->comment('Дата счета'),
            'subscription' => $this->float(2)->comment('Абонентская'),
            'one_time' => $this->float(2)->comment('Разовые'),
            'online' => $this->float(2)->comment('В сети'),
            'roaming' => $this->float(2)->comment('Роуминг'),
            'cost' => $this->float(2)->comment('Итого'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('phone_bill');
    }
}
