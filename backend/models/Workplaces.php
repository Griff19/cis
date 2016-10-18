<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "workplaces".
 *
 * @property integer $id
 * @property integer $room_id
 * @property boolean $mu
 * @property string $workplaces_title
 * @property integer $voip
 * @property ip $ip
 * @property integer $status
 * @property integer $voip_number
 * @property string $email
 * @property string $snp
 * @property string $cellnumber
 * @property Employees[] owner
 * @property mixed inventory
 */
class Workplaces extends \yii\db\ActiveRecord
{
    public $_owner;
    public $voip;


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
            [['room_id', 'branch_id'], 'required'],
            [['room_id', 'branch_id', 'voip'], 'integer'],
            [['workplaces_title'], 'string', 'max' => 255],
            //['ip', 'ip'],
            [['mu'], 'boolean'],
            //['inventoryDate', 'date']
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
            'voip' => 'Вн.Номер',
            'mu' => 'МП*',
            'inventoryDate' => 'Инвентаризация'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom(){
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch(){
        return $this->hasOne(Branches::className(), ['id' => 'branch_id']);
    }

    /**
     * @return $this
     */
    public function getOwner(){
        $res = $this->hasMany(Employees::className(), ['id' => 'employee_id'])->viaTable('wp_owners', ['workplace_id' => 'id']);
        //var_dump($res);
        return $res;
    }

    public function getVoips(){
        return $this->hasMany(VoipNumbers::className(), ['workplace_id' => 'id']);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public static function getNetintsProvider($id){
        $queryNet = (new Query())
            ->select("title, devices.id AS dev_id, device_note, parent_device_id, type_id, netints.id AS net_id, netints.ipaddr AS ip")
            ->from("devices, netints, device_type")
            ->where("parent_device_id > 0 AND netints.device_id = devices.id AND devices.type_id = device_type.id");

        $query = (new Query())
            ->select("devices.workplace_id AS wp_id, title, s.dev_id AS dev_id, s.device_note, s.ip AS ip")
            ->from(['s' => $queryNet, 'devices'])
            ->where("devices.id = s.parent_device_id")
            ->andWhere(['workplace_id' => $id]);

        $provider = new ActiveDataProvider(['query' => $query]);
        return $provider;
    }

    /**
     * @return $this
     */
    public function getNetints(){
        return $this->hasMany(Netints::className(), ['device_id' => 'id'])->viaTable('devices', ['workplace_id' => 'id']);
    }

    /**
     * Связываем рабочее место и акты инвентаризации
     */
    public function getInventory(){
        return $this->hasMany(InventoryActs::className(), ['workplace_id' => 'id'])->orderBy('act_date DESC');
    }

    /**
     * Получаем последнюю дату связанного Акта инвентаризации
     * @return mixed
     */
    public function getInventoryDate(){
        if ($this->inventory)
            return $this->inventory[0]->act_date;
        else
            return false;
    }

    public function getTitle($id){
        $workplace = Workplaces::find()->select('workplaces_title')->where(['id' => $id])->asArray()->one();
        return $workplace['workplaces_title'];
    }
}
