<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * Модель для таблицы "cell_numbers".
 *
 * @property integer $id
 * @property string $cell_number
 * @property integer $employee_id
 * @property integer $status
 *
 * @property Employees $employee
 */
class CellNumbers extends \yii\db\ActiveRecord
{
    public $file;
    public static $progress;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cell_numbers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'status'], 'integer'],
            ['status', 'unique', 'targetAttribute' => ['status', 'employee_id']],
            [['file'], 'file'],
            [['cell_number'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cell_number' => 'Сотовый номер',
            'employee_id' => 'Владелец',
            'status' => 'Статус',
            'file' => 'Файл'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    /**
     * получаем максимальный статус для последовательности номеров одного сотрудника
     * @return $this
     */
    public function getMaxStatus(){
        $max_status = (new Query())
            ->select('status')
            ->from('cell_numbers')
            ->where(['employee_id' => $this->employee_id])
            ->orderBy('status DESC')
            ->limit(1)
            ->scalar();

        return $max_status;
    }
}
