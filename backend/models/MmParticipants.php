<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mm_participants".
 *
 * @property integer $id
 * @property integer $mm_id
 * @property integer $employee_id
 * @property string $employee_name
 */
class MmParticipants extends \yii\db\ActiveRecord
{
    public $employee_name;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mm_participants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mm_id', 'employee_id'], 'integer'],
			['employee_name', 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mm_id' => 'Ид Протокола',
            'employee_id' => 'Ид Сотрудника',
			'employee_name' => 'ФИО Сотрудника'
        ];
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEmployee() {
		return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
	}
}
