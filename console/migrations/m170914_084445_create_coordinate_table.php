<?php

use yii\db\Migration;

/**
 * Handles the creation of table `coordinate`.
 */
class m170914_084445_create_coordinate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('coordinate', [
            'id' => $this->primaryKey(),
	        'workplace_id' => $this->integer()->comment('идентификатор рабочего места'),
	        'floor' => $this->smallInteger()->notNull()->defaultValue(1)->comment('номер этажа, слоя на карте'),
	        'x' => $this->decimal()->notNull()->defaultValue(0)->comment('координата x'),
            'y' => $this->decimal()->notNull()->defaultValue(0)->comment('координата y'),
	        'balloon' => $this->char(256)->comment('содержимое всплывающего окна'),
	        'preset' => $this->char(256)->comment('вид метки'),
	        'comment' => $this->text()->comment('комментарий к метке'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('coordinate');
    }
}
