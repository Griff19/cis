<?php
/**
 * Модель таблицы устройств для временного РМ "tmp_device".
 */
namespace backend\models;

use Yii;

/**
 * @property integer $tmp_workplace_id
 * @property integer $devices_id
 */
class TmpDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmp_workplace_id', 'devices_id'], 'required'],
            [['tmp_workplace_id', 'devices_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tmp_workplace_id' => 'Рабочее место',
            'devices_id' => 'ИД Устройства',
        ];
    }
}
