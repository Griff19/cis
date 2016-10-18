<?php

namespace backend\models;

use Yii;
use backend\models\Workplaces;

/**
 * This is the model class for table "wp_owners".
 *
 * @property integer $workplace_id
 * @property integer $employee_id
 * @property boolean $event
 * @property string $date
 * @property integer $status
 */
class WpOwners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_owners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplace_id', 'employee_id', 'status'], 'integer'],
            [['event'], 'boolean'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'workplace_id' => 'Рабочее место',
            'employee_id' => 'Ответственный',
            'event' => 'Event',
            'date' => 'Date',
        ];
    }

    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_id']);
    }

    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }
}
