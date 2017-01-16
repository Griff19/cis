<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы "message".
 *
 * @property integer $id идентификатор сообщения-задачи
 * @property integer $user_id идентификатор пользователя-получателя
 * @property string $subject тема сообщения-задачи
 * @property integer $type тип 1 - задача, 2 - сообщение
 * @property string $content содержимое
 * @property integer $status статус 0 - удалено, 1 - новое, 2 - прочитано
 * @property integer $from_user_id идентификатор пользователя-отправителя
 * @property string $date_send дата отправки-создания
 * @property string $date_complete дата-срок выполнения (для задачи)
 * @property string $target класс окем создана задача
 * @property integer $target_id идентификатор экземпляра класса создавшего задачу
 * @property Employees employeeTo модель сотрудника кому
 * @property Employees employeeFrom модель сотрудника от кого
 * @property User userTo модель пользователя кому
 * @property User userFrom модель пользователя от кого
 * @property string stringType строковое представление типа сообщения
 * @property string stringStatus строковое представление статуса сообщения
 */
class Message extends ActiveRecord
{
	const STATUS_DELETED = 0;
	const STATUS_CREATED = 1;
	const STATUS_READ = 2;

	const TYPE_TASK = 1;
	const TYPE_MESSAGE = 2;

	public function getStringStatus() {
		$arr = [
			0 => 'Удалено',
			1 => 'Новое',
			2 => 'Прочитано'
		];
		return $arr[$this->status];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'message';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['user_id', 'type', 'status', 'from_user_id', 'target_id'], 'integer'],
			[['subject', 'content'], 'required'],
			[['content', 'date_send', 'date_complete'], 'string'],
			[['subject', 'target'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'user_id' => 'Пользователь Кому',
			'subject' => 'Тема',
			'type' => 'Тип сообщения',
			'content' => 'Содержание',
			'status' => 'Статус',
			'from_user_id' => 'Пользоваель От кого',
			'date_send' => 'Дата отправки',
			'date_complete' => 'Дата выполнения',
			'target' => 'Объект',
			'target_id' => 'ИдОбъекта',
			'stringType' => 'Тип сообщения',
			'stringStatus' => 'Статус'
		];
	}

	/**
	 * Создаем новую задачу
	 * Используется при сохранении документов чтобы напомнить пользовател о дальнейших действиях
	 * @param null $user_id идентификатор пользователя
	 * @param string $subject тема
	 * @param integer $type тип сообщения 1 - задача, 2 - сообщение
	 * @param string $content тело сообщения
	 * @param null $target
	 * @param null $target_id
	 * @return bool|string
	 */
	public static function Create($user_id = null, $subject, $type, $content, $target = null, $target_id = null) {
		$task = new Message();
		$task->user_id = $user_id;
		$task->subject = $subject;
		$task->type = $type;
		$task->content = $content;
		$task->target = $target;
		$task->target_id = $target_id;
		$task->status = self::STATUS_CREATED;
		if ($task->save())
			return true;
		else
			return serialize($task->errors);
	}

	/**
	 * Количество новых сообщений для пользователя
	 * @return int|string
	 */
	public static function CountNewMessage() {
		$countNew = Message::find()->where(['user_id' => Yii::$app->user->id])
			->andWhere(['status' => self::STATUS_CREATED])
			->count();
		return $countNew;
	}

	/**
	 * Количество сообщений для пользователя
	 * @return int|string
	 */
	public static function CountMessage() {
		$count = Message::find()->where(['user_id' => Yii::$app->user->id])->count();
		return $count;
	}

	public function getUserTo() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getUserFrom() {
		return $this->hasOne(User::className(), ['id' => 'from_user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEmployeeTo() {
		return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('user', ['id' => 'user_id']);
	}

	/**
	 * @return $this
	 */
	public function getEmployeeFrom() {
		return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('user', ['id' => 'from_user_id']);
	}

	/**
	 * @return string
	 */
	public function getStringType() {
		$arr = [
			1 => 'Задача',
			2 => 'Сообщение'
		];

		return $arr[$this->type];
	}
}
