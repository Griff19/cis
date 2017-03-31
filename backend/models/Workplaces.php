<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * Модель "Рабочее место", использует таблицу "workplaces".
 *
 * @property integer $id              Идентификатор рабочего места
 * @property integer $room_id         Идентификатор кабинета
 * @property boolean $mu              Многопользовательское рабочее место
 * @property string $workplaces_title Описание рабочего места
 * @property integer $voip            Внутренний номер
 * @property integer $status          Статус рабочего места
 * @property Employees[] owner        Владелец рабочего места
 * @property mixed inventory          Связь с документами "Акт инвентаризации"
 * @property string summary           Сводная информация о рабочем месте
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
            [['room_id', 'branch_id', 'voip'], 'integer', 'message' => 'Необходимо заполнить {attribute}'],
            [['workplaces_title'], 'string', 'max' => 255],
            [['mu'], 'boolean'],
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
            'inventoryDate' => 'Инвентаризация',
            'summary' => 'Рабоче место'
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

    /**
     * @param $id
     * @return mixed
     */
    public function getTitle($id){
        $workplace = Workplaces::find()->select('workplaces_title')->where(['id' => $id])->asArray()->one();
        return $workplace['workplaces_title'];
    }

    /**
     * Возвращает полное наименование рабочего места
     * $mode = 0 - для вывода в строку, 1 - для вывода в "столбец"
     * @return string
     */
    public function getSummary($mode = 0)
    {
        $res = '';
        if ($mode == 0)
            $sep = ", ";
        else
            $sep = "\n";

        $res .= '№' . $this->id . ', ';
        $res .= $this->room->branch->branch_title . $sep;
        $res .= $this->room->room_title . $sep;
        $res .= $this->workplaces_title;

        return $res;

    }
}
