<?php

namespace backend\models;

/**
 * This is the model class for table "tmp_moving".
 *
 * @property integer $id
 * @property integer $device_id
 * @property integer $workplace_from
 * @property integer $workplace_where
 * @property integer $user_id
 * @property integer $status
 * @property Devices device
 * @property Workplaces workplaceFrom
 * @property Workplaces workplaceWhere
 * @property User user
 */
class TmpMoving extends \yii\db\ActiveRecord
{
    const STATUS_DEFAULT = 1; // Устройство в процессе перемещения
    
    public $summary;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_moving';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'workplace_from', 'workplace_where', 'user_id'], 'required'],
            [['device_id', 'workplace_from', 'workplace_where', 'user_id', 'status'], 'integer'],
            [['device_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Устройство',
            'summary' => 'Общая информция',
            'workplace_from' => 'Откуда',
            'workplaceFrom.summary' => 'Откуда',
            'workplace_where' => 'Куда',
            'workplaceWhere.summary' => 'Куда',
            'user_id' => 'Пользователь',
            'user.employee.snp' => 'Пользователь',
            'status' => 'Статус',
        ];
    }
    
    /**
     * Связываем с моделью устройств
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Devices::className(), ['id' => 'device_id']);
    }
    
    /**
     * Связываем с моделью рабочего места
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplaceFrom()
    {
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_from']);
    }
    
    /**
     * Связываем с моделью рабочего места
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplaceWhere()
    {
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_where']);
    }
    
    /**
     * Связываем с моделью пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
