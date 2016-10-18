<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "netints".
 *
 * @property integer $id
 * @property string $mac
 * @property string $vendor
 * @property string $ipaddr
 * @property string $domain_name
 * @property integer $type
 * @property integer $port_count
 * @property integer $device_id
 */
class Netints extends \yii\db\ActiveRecord
{
    const PHISICAL = 0;
    const AGGREGATE = 1;
    const VIRTUAL = 2;
    const WIRELESS = 3;

    /**
     * Функция используется для фильтра
     * @return array
     */
    public static function arrTypes(){
        return [
            0 => 'Физический',
            1 => 'Агрегированный',
            2 => 'Виртуальный',
            3 => 'Беспроводной'
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'netints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac'], 'match', 'pattern' => '/(^([A-F|a-f|0-9]{2}[:\-]){5}[A-F|a-f|0-9]{2}$)|' .
                                                '(^([A-F|a-f|0-9]{6}[:\-])[A-F|a-f|0-9]{6}$)|' .
                                                '(^([A-F|a-f|0-9]{4}\.){2}[A-F|a-f|0-9]{4}$)|' .
                                                '(^([A-F|a-f|0-9]{12})$)/'],

            [['mac'], 'string', 'max' => 17],
            [['mac'], 'default', 'value' => '00:00:00:00:00:00'],
            [['ipaddr'], 'default', 'value' => '192.168.0.0'],
            [['ipaddr'], 'match', 'pattern' => '/^(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])){3}$/'],
            [['ipaddr'], 'string', 'max' => 15, 'tooLong' => 'IP введен не верно'],
            [['type', 'port_count', 'device_id'], 'integer'],
            ['port_count', 'default', 'value' => 1],
            ['type', 'default', 'value' => 0],
            [['vendor', 'domain_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'mac' => 'Mac адрес',
            'vendor' => 'Производитель',
            'ipaddr' => 'ip адрес',
            'domain_name' => 'Доменное имя',
            'type' => 'Тип',
            'port_count' => 'Портов',
            'device_id' => 'Устройство',
        ];
    }

    public function getDevices() {
        return $this->hasOne(Devices::className(), ['id' => 'device_id']);
    }

    public function getDeviceType() {
        return $this->hasOne(DeviceType::className(), ['id' => 'type_id'])->via('devices');
    }


}
