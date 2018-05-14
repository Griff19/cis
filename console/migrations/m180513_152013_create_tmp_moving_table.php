<?php

use yii\db\Migration;

/**
 * Создание таблицы `tmp_moving`
 * для хранения данных о перемещаемых устройствах
 */
class m180513_152013_create_tmp_moving_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tmp_moving', [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull()->unique()->comment('ИД перемещаемого устройства'),
            'workplace_from' => $this->integer()->notNull()->comment('ИД рабочего места отправления'),
            'workplace_where' => $this->integer()->notNull()->comment('ИД рабочего места назначения'),
            'user_id' => $this->integer()->notNull()->comment('ИД пользователя'),
            'status' => $this->integer()->comment('статус перемещения'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tmp_moving');
    }
}
