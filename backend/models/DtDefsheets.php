<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dt_defsheets".
 * Модель описывает документ "Акт Списания"
 * @property integer $id
 * @property string $date_create
 * @property string $date_confirm
 * @property integer $status
 * @property string $employee_name
 * @property integer $employee_id
 * @property integer $user_id
 * @property Employees employee
 * @property Employees actor
 */
class DtDefsheets extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_SAVED = 1;
    const STATUS_CONFIRM = 3;

    public $employee_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_defsheets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create', 'date_confirm'], 'safe'],
            ['employee_name', 'string'],
            [['status', 'employee_id', 'user_id'], 'integer'],
            [['employee_id', 'employee_name'], 'required'],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_create' => 'Дата Создания',
            'date_confirm' => 'Дата Подтверждения',
            'status' => 'Статус',
            'employee_name' => 'Имя сотрудника',
            'employee_id' => 'Сотрудник',
            'user_id' => 'Исполнитель'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    /**
     * @return $this
     */
    public function getActor() {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('user', ['id' => 'user_id']);
    }

}
