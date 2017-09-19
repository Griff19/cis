<?php

use yii\db\Migration;

/**
 * Создаем таблицы для подсистемы "Задачи"
 */
class m180915_052950_create_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //Создаем таблицу для хранения типов задач
        $this->createTable('task_type', [
            'id' => $this->primaryKey(),
            'name' => $this->char(128)->notNull()->comment('наименование типа задачи'),
            'description' => $this->text()->comment('описание типа задачи, помогает при выборе типа'),
            'prg_class' => $this->char(64)->notNull()->comment('префикс класса для данного типа задач')
        ]);

        $this->addCommentOnTable('task_type', 'Таблица модуля Задачи, хранит типы задач');

        //Создаем таблицу для хранения задач
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'task_type_id' => $this->integer()->notNull()->comment('ссылка на тип задачи: task_type'),
            'name' => $this->char(255)->notNull()->comment('наименование задачи'),
            'description' => $this->text()->notNull()->comment('описание задачи'),
            'author_id' => $this->integer()->notNull()->comment('кем поставлена задача, текущий пользователь'),
            'accept_user_id' => $this->integer()->comment('кто принял задачу, ввел в систему'),
            'priority_id' => $this->integer()->notNull()->comment('приоритет задачи'),
            'task_category_id' => $this->integer()->comment('категория задачи'),
            'normative_time' => $this->decimal()->comment('нормативное время выполнения задачи'),
            'is_accumulation' => $this->boolean()->notNull()->comment('признак накопления, переносится ли задача на следующий день'),
            'is_active' => $this->boolean()->notNull()->comment('признак активности, требует ли задача выполнения'),
            'is_scheduled' => $this->boolean()->notNull()->comment('выполнение задачи по расписанию или однократно'),
            'one_time_exec_date' => $this->timestamp()->comment('дата однократного выполнения'),
            'schedule_date_start' => $this->timestamp()->comment('дата начала работы по расписанию'),
            'schedule_date_stop' => $this->timestamp()->comment('дата окончания работы по расписанию'),
            'schedule' => $this->char(32)->comment('расписание в формате cron'),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addCommentOnTable('task', 'Таблица модуля Задачи, хранит основную информацию о задачах');

        //Создаем таблицу ассоциации с пользователями
        $this->createTable('task_assign_user', [
            'task_id' => $this->integer()->notNull()->comment('идентификатор задачи'),
            'user_id' => $this->integer()->notNull()->comment('идентификатор пользователя')
        ]);

        $this->addCommentOnTable('task_assign_user', 'Таблица модуля Задачи, хранит ассоциации с пользователями');

        //Создаем таблицу шагов/этапов задачи
        $this->createTable('task_step', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull()->comment('идентификатор задачи из таблицы: task'),
            'step_num' => $this->smallInteger()->notNull()->comment('номер шага'),
            'name' => $this->char(255)->notNull()->comment('наименование шага'),
            'description' => $this->text()->notNull()->comment('описание шага'),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addCommentOnTable('task_step', 'Таблица модуля Задачи, хранит шаги, этапы задачи (шаблон)');

        //Создаем таблицу для хранения выполняемых задач
        $this->createTable('task_exec', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull()->comment('ссылка на задачу: task'),
            'task_type_id' => $this->integer()->notNull()->comment('ссылка на тип задачи: task_type'),
            'name' => $this->char(255)->notNull()->comment('наименование выполняемой задачи'),
            'description' => $this->text()->comment('описание выполняемой задачи'),
            'author_id' => $this->integer()->notNull()->comment('кем поставлена задача, сотрудник'),
            'accept_user_id' => $this->integer()->comment('кто принял задачу, внес в систему'),
            'assign_user_id' => $this->integer()->notNull()->comment('кому назначена задача'),
            'priority_id' => $this->integer()->notNull()->comment('приоритет задачи'),
            'task_category_id' => $this->integer()->comment('категория задачи'),
            'normative_time' => $this->decimal()->comment('нормативное время выполнения задачи'),
            'is_accumulation' => $this->boolean()->notNull()->comment('признак накопления, переносится ли задача на следующий день'),
            'task_status_id' => $this->integer()->notNull()->comment('статус задачи: task_status'),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addCommentOnTable('task_exec', 'Таблица модуля Задачи, хранит выполняемые задачи');

        //Создаем таблицу шагов выполняемых задач
        $this->createTable('task_exec_step', [
            'id' => $this->primaryKey(),
            'task_exec_id' => $this->integer()->notNull()->comment('ссылка на выполняемую задачу: task_exec'),
            'step_num' => $this->smallInteger()->notNull()->comment('номер шага'),
            'name' => $this->char(255)->notNull()->comment('наименование шага'),
            'description' => $this->text()->notNull()->comment('описание шага'),
            'done_ratio' => $this->smallInteger()->notNull()->comment('процент выполнения текущего шага'),
            'task_step_status_id' => $this->integer()->notNull()->comment('статус шага'),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addCommentOnTable('task_exec_step', 'Таблица модуля Задачи, хранит шаги выполняемых задач');

        //Создаем таблицу комментариев для выполняемых задач
        $this->createTable('task_exec_comment', [
            'id' => $this->primaryKey(),
            'task_exec_id' => $this->integer()->notNull(),
            'task_exec_step_id' => $this->integer(),
        ]);
    }

    //todo: Дописать миграцию таблиц...
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('task_type');
        $this->dropTable('task');
    }
}
