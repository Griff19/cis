<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "voip_numbers".
 *
 * @property integer $id
 * @property integer $voip_number
 * @property string $secret
 * @property string $description
 * @property string $context
 * @property integer $device_id
 * @property integer $workplace_id
 * @property integer $status
 */
class VoipNumbers extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voip_numbers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voip_number', 'device_id', 'workplace_id', 'status'], 'integer'],
            ['voip_number', 'default'],
            //['status', 'unique', 'targetAttribute' => ['status', 'device_id']],
            [['secret', 'description', 'context'], 'string'],
            ['file', 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voip_number' => 'Номер',
            'secret' => 'Secret',
            'description' => 'Описание',
            'context' => 'Контекст',
            'device_id' => 'ИД устройства',
            'status' => 'Статус',
            'file' => 'Файл'
        ];
    }
}
