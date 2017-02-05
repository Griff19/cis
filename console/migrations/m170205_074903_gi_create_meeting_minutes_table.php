<?php

use yii\db\Migration;

class m170205_074903_gi_create_meeting_minutes_table extends Migration
{
    public function up()
    {
		//основная таблица протоколов
		$this->createTable('meeting_minutes', [
			//ид - первичный ключ
			'id' => $this->primaryKey(),
			//номер протокола
			'doc_num' => $this->string()->comment('Номер документа'),
			//дата содания пртокола, проведения встречи
			'doc_date' => $this->dateTime()->comment('Дата документа'),
		]);

		//таблица участников
		$this->createTable('mm_participants', [
			//ид - первичный ключ
			'id' => $this->primaryKey(),
			//ид таблицы протоколов
			'mm_id' => $this->integer()->comment('Идентификатор протокола'),
			//ид сотрудика
			'employee_id' => $this->integer()->comment('Идентификатор сотрудника'),
		]);

		//таблица повестки
		$this->createTable('mm_agenda', [
			//ид - первичный ключ
			'id' => $this->primaryKey(),
			//ид таблицы протоколов
			'mm_id' => $this->integer()->comment('Идентификтор протокола'),
			//содержание повестики
			'content' => $this->text()->comment('Содержание повестки')
		]);

		//таблица предложений
		$this->createTable('mm_offer', [
			//ид - первичный ключ
			'id' => $this->primaryKey(),
			//ид таблицы протоколов
			'mm_id' => $this->integer()->comment('Идентификатор протокола'),
			//содержание предложения
			'content' => $this->text()->comment('Содержание предложения')
		]);

		//таблица принятых решений
		$this->createTable('mm_decision', [
			//ид - первичный ключ
			'id' => $this->primaryKey(),
			//ид таблицы протоколов
			'mm_id' => $this->integer()->comment('Идентификатор протокола'),
			//содержание решений
			'content' => $this->text()->comment('Содержание решения'),
			//дата исполнения
			'due_date' => $this->date()->comment('Дата исполнения')
		]);

		$this->addCommentOnTable('meeting_minutes', 'Протоколы встреч - основная таблица');
		$this->addCommentOnTable('mm_participants', 'Участники встречи');
		$this->addCommentOnTable('mm_agenda', 'Повестки встречи');
		$this->addCommentOnTable('mm_offer', 'Предложения, выдвинутые на встрече');
		$this->addCommentOnTable('mm_decision', 'Решения, принятые на встрече');

		return true;
    }

    public function down()
    {
        $this->dropTable('meeting_minutes');
		$this->dropTable('mm_participants');
		$this->dropTable('mm_agenda');
		$this->dropTable('mm_offer');
		$this->dropTable('mm_decision');

        return true;
    }

}
