<?php

use yii\db\Migration;

/**
 * Создание таблицы временных рабочих мест `tmp_workplace`.
 */
class m180412_030127_create_tmp_workplace_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('tmp_workplace', [
            'id' => $this->primaryKey(),
            'workplaces_id' => $this->integer()->comment('идентификатор рабочего места'),
            'title' => $this->string(256)->comment('описание рабочего места'),
            'status' => $this->integer()->comment('статус'),
        ]);

        $this->addCommentOnTable('tmp_workplace', 'Таблица временных рабочих мест');

        $this->createTable('tmp_device', [
            'tmp_workplace_id' => $this->integer()->comment('идентификатор временного рабочего места'),
            'devices_id' => $this->integer()->comment('идентификатор устройства'),
            'title' => $this->string(256)->comment('заметка'),
            'status' => $this->integer()->comment('статус'),
        ]);

        $this->addPrimaryKey('tmp_device_pk', 'tmp_device', ['tmp_workplace_id', 'devices_id']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('tmp_workplace');
        $this->dropTable('tmp_device');
    }
}
