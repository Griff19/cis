<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "story_workplace".
 *
 * @property integer $id
 * @property integer $id_wp
 * @property integer $id_employee
 * @property string $date_up
 * @property integer $event
 */
class StoryWorkplace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_workplace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_wp', 'id_employee', 'event'], 'integer'],
            [['date_up'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_wp' => 'Id Wp',
            'id_employee' => 'Id Employee',
            'date_up' => 'Date Up',
            'event' => 'Event',
        ];
    }

    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'id_employee']);
    }
}
