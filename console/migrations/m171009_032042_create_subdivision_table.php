<?php

use yii\db\Migration;

/**
 * Handles the creation of table `units`.
 */
class m171009_032042_create_subdivision_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('subdivision', [
            'id' => $this->primaryKey(),
            'name' => $this->char(64)->notNull()->comment('наименование подразделения'),
            'branch_id' => $this->integer()->comment('идентификатор филиала'),
            'employee_id' => $this->integer()->comment('начальник подразделения'),
            'subdivision_id' => $this->integer()->comment('главное подразделение'),
            'description' => $this->text()->comment('описание подразделения')
        ]);

        $this->addCommentOnTable('subdivision', 'Подразделения в филиалах');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('subdivision');
    }
}
