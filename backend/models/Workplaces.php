<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use backend\models\Coordinate;


/**
 * Модель "Рабочее место", использует таблицу "workplaces".
 *
 * @property integer $id              Идентификатор рабочего места
 * @property integer $room_id         Идентификатор кабинета
 * @property integer $branch_id       Идентификатор филиала
 * @property boolean $mu              Многопользовательское рабочее место
 * @property string $workplaces_title Описание рабочего места
 * @property integer $voip            Внутренний номер
 * @property integer $status          Статус рабочего места
 * @property Employees[] owner        Владелец рабочего места
 * @property mixed inventory          Связь с документами "Акт инвентаризации"
 * @property string summary           Сводная информация о рабочем месте
 * @property Coordinate[] coordinate    Координаты рабочего места на карте
 * @property Coordinate[] allCoordinate Все координаты доступные на данном слое
 * @property Branches branch
 * @property Rooms room
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
        return $this->hasOne(Rooms::class, ['id' => 'room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch(){
        return $this->hasOne(Branches::class, ['id' => 'branch_id']);
    }

    /**
     * @return $this
     */
    public function getOwner(){
        $res = $this->hasMany(Employees::class, ['id' => 'employee_id'])->viaTable('wp_owners', ['workplace_id' => 'id']);
        //var_dump($res);
        return $res;
    }

    public function getVoips(){
        return $this->hasMany(VoipNumbers::class, ['workplace_id' => 'id']);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public static function getNetintsProvider($id){
        // #190824-1 Исправление запроса >>>
        $queryDev = (new Query())->select("id, type_id")->from("devices")->where(['workplace_id' => $id]);
        $queryPar = (new Query())->select("parent_device_id")->from("devices")->where(['workplace_id' => $id])->andWhere("parent_device_id > 0");
        $queryDev2 = (new Query())->select("id, type_id")->from("devices")->where(['IN', 'parent_device_id', $queryPar]);
        
        $queryNet = $queryDev->union($queryDev2);
        
        $query = (new Query())
            ->select("device_type.title AS title, device_id AS dev_id, domain_name, ipaddr AS ip, netints.mac AS mac")
            ->from(['t_devices' => $queryNet, 'netints', 'device_type'])
            ->where("netints.device_id = t_devices.id")->andWhere("device_type.id = t_devices.type_id");
        
//        $queryNet = (new Query())
//            ->select("title, devices.id AS dev_id, device_note, parent_device_id, type_id, netints.id AS net_id,
//                netints.ipaddr AS ip, domain_name")
//            ->from("devices, netints, device_type")
//            ->where("netints.device_id = devices.id AND devices.type_id = device_type.id");
//
//        $query = (new Query())
//            ->select("devices.workplace_id AS wp_id, title, s.dev_id AS dev_id, s.device_note, s.ip AS ip, domain_name")
//            ->from(['s' => $queryNet, 'devices'])
//            ->where("devices.id = s.parent_device_id")
//            ->andWhere(['workplace_id' => $id]);
        // #190824-1 Исправление запроса <<<
        $provider = new ActiveDataProvider(['query' => $query]);
        // #200127-2 отключение пагинации:
        $provider->pagination = false;
        return $provider;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetints(){
        return $this->hasMany(Netints::class, ['device_id' => 'id'])->viaTable('devices', ['workplace_id' => 'id']);
    }

    /**
     * Связываем рабочее место и акты инвентаризации
     */
    public function getInventory(){
        return $this->hasMany(InventoryActs::class, ['workplace_id' => 'id'])->orderBy('act_date DESC');
    }

	/**
	 * Связываем рабочее место и координаты
	 * @return \yii\db\ActiveQuery
	 */
    public function getCoordinate() {
    	return $this->hasMany(Coordinate::class, ['workplace_id' => 'id']);
    }

	/**
     * Выбираем все точки
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public static function getAllCoordinate($floor, $branch) {
    	return Coordinate::find()->where(['workplace_id' => 0])->andWhere(['floor' => $floor])->andWhere(['branch_id' => $branch])->all();
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
     * ??? Не понятный метод, на знаю зачем он нужен
     * @param $id
     * @return mixed
     */
    public static function getTitle($id){
        $workplace = Workplaces::find()->select('workplaces_title')->where(['id' => $id])->asArray()->one();
        return $workplace['workplaces_title'];
    }
    
    /**
     * Возвращает полное наименование рабочего места
     * @param int $mode 0 - для вывода в строку, 1 - для вывода в "столбец
     * @return string
     */
    public function getSummary($mode = 0)
    {
        $res = '';
        if ($mode == 0) $sep = ", ";
        else $sep = "\n";

        $res .= '№' . $this->id . ', ';
        $res .= $this->branch->branch_title . $sep;
        $res .= $this->room->room_title . $sep;
        $res .= $this->workplaces_title;

        return $res;
    }
}
