<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "emails".
 *
 * @property integer $id
 * @property string $email_address
 * @property integer $employee_id
 * @property integer $status
 *
 * @property Employees $employee
 */
class Emails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'status'], 'integer'],
            ['status', 'unique', 'targetAttribute' => ['status', 'employee_id']],
            [['email_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email_address' => 'Email',
            'employee_id' => 'Сотрудник',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }
}
