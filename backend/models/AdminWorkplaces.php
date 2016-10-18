<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "workplaces".
 *
 * @property integer $id
 * @property string $branch_title
 * @property string $room_title
 * @property boolean $mu
 * @property string $workplaces_title
 * @property string $device_type
 * @property integer $device_id
 * @property string $device_note
 * @property string $ip
 * @property string $mac
 * @property string $domain_name
 * @property integer $status
 * @property integer $voip_number
 * @property string $email
 * @property string $snp
 * @property string $cellnumber
 * @property integer $tab
  */
class AdminWorkplaces extends \yii\db\ActiveRecord
{
    public $_owner;
    public $device_type;
    public $device_id;
    public $device_note;
    public $ip;
    public $mac;
    public $domain_name;
    public $branch_title;
    public $room_title;
    public $status;
    public $voip_number;
    public $email;
    public $snp;
    public $cellnumber;
    public $jobtitle;


    public $tab;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'workplaces';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['branch_title', 'string'],
            ['status', 'integer'],
            ['room_title', 'string'],
            ['workplaces_title', 'string'],
            ['voip_number', 'integer'],
            ['snp', 'string'],
            ['cellnumber', 'integer'],
            ['email', 'string'],
            ['jobtitle', 'string'],
            ['device_type', 'string'],
            ['device_id', 'integer'],
            ['device_note', 'string'],
            ['ip', 'ip'],
            ['mac', 'string'],
            ['domain_name', 'string'],
            ['tab', 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Подразделение',
            'room_id' => 'Отдел/Кабинет',
            'workplaces_title' => 'Рабочее место',
            '_owner' => 'Ответственный',
            'voip_number' => 'Вн.Номер',
            'mu' => 'МП*',
            'ip' => 'IP-адрес',
            'mac' => 'MAC-адрес',
            'domain_name' => 'Имя узла'
        ];
    }

    /**
     * Функция готовит провайдер для вывода данных по устройствам на рабочем месте
     * @param $id
     * @return ActiveDataProvider
     */
    public static function devicesProvider($id){
        $query = (new Query())
            ->select([
                'parent_type_title' => 'parent_type.title',
                'parent_devices_id' => 'parent_devices.id',
                'device_type_title' => 'device_type.title',
                'devices_brand' => 'devices.brand',
                'devices_model' => 'devices.model',
                'devices_sn' => 'devices.sn',
                'devices_id' => 'devices.id',
                'devices_specification' => 'devices.specification',
                'devices_device_note' => 'devices.device_note'
            ])->from('devices')
            ->leftJoin(['parent_devices' => 'devices'], 'parent_devices.id = devices.parent_device_id')
            ->leftJoin(['parent_type' => 'device_type'], 'parent_type.id = parent_devices.type_id')
            ->leftJoin('device_type', 'device_type.id = devices.type_id')
            ->where(['devices.workplace_id' => $id])
            ->orWhere(['parent_devices.workplace_id' => $id])
            ->orderBy('parent_devices.type_id DESC, devices.type_id DESC');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $provider;
    }
    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public static function netintsProvider($id){
        $query = (new Query())
            ->select([
                'workplace_id' => 'workplaces.id',
                'parent_type_title' => 'parent_type.title',
                'parent_devices_device_note' => 'parent_devices.device_note',
                'devices_id' => 'devices.id',
                'device_type_title' => 'device_type.title',
                'devices_device_note' => 'devices.device_note',
                'devices_parent_device_id' => 'devices.parent_device_id',
                'netints_id' => 'netints.id',
                'netints_type' => 'netints.type',
                'netints_mac' => 'netints.mac',
                'netints_ipaddr' => 'netints.ipaddr',
                'netints_domain_name' => 'netints.domain_name',
                'netints_port_count' => 'netints.port_count'])
            ->from('devices')
            ->leftJoin(['parent_devices' => 'devices'], 'parent_devices.id = devices.parent_device_id')
            ->leftJoin('workplaces', 'workplaces.id = devices.workplace_id OR workplaces.id = parent_devices.workplace_id')
            ->leftJoin('device_type', 'device_type.id = devices.type_id')
            ->leftJoin(['parent_type' => 'device_type'], 'parent_type.id = parent_devices.type_id')
            ->leftJoin('netints', 'netints.device_id = devices.id')
            ->where('netints.type IS NOT NULL AND workplaces.id = ' . $id);

        $provider = new ActiveDataProvider(['query' => $query]);
        return $provider;
    }

    /**
     * массив ip-адресов
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayIps(){
        return Netints::find()->select('ipaddr as value, ipaddr as label')
            ->where("ipaddr > '192.168.0.0'")->orderBy('ipaddr')->asArray()->all();
    }

    /**
     * массив voip-номеров
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayVoips(){
        return VoipNumbers::find()->select('voip_number as value, voip_number as label')
            ->where('workplace_id > 0')->orderBy('workplace_id')->asArray()->all();
    }

    /**
     * массив mac-адресов
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayMacs(){
        return Netints::find()->select('mac as value, mac as label')
            ->where("mac > '00:00:00:00:00:00'")->orderBy('mac')->asArray()->all();
    }

    /**
     * массив доменных имен
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayDomains(){
        return Netints::find()->select('domain_name as value, domain_name as label')
            ->where("domain_name > ''")->orderBy('domain_name')->asArray()->all();
    }

}
