<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * This is the model class for table "devices".
 *
 * @property integer id
 * @property integer type_id
 * @property string brand
 * @property string model
 * @property string sn
 * @property string device_note
 * @property string specification
 * @property string imei1
 * @property string imei2
 * @property integer workplace_id
 * @property integer parent_device_id
 * @property boolean dev_comp
 * @property mixed deviceType
 * @property mixed fake_device помечает устройство как фейк
 *
 */
class Devices extends \yii\db\ActiveRecord
{
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_NOSN = 'nosn';

    public $branch_id;
    public $room_id;
    public $count;
    public $dev_comp; //комплектующее
    //переменные необходимы для сложной выборки устройств
    public $dt_title;
    public $wp_title;
    public $snp;

    public $curr_type;
    public $device_mac; //для отбора по мак-адресам

    public $chekMode; //для переключения сценариев

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplace_id','type_id'], 'required'],
            ['sn', 'required', 'when' => function($model){return false;},
            'whenClient' => 'function(attribute, value){return $("#devices-chekmode").val() == 0;}'],
            [['sn', 'imei1'],'unique', 'on' => self::SCENARIO_INSERT],
            [['workplace_id','type_id', 'parent_device_id', 'fake_device'], 'integer'],
            [['imei1', 'imei2'], 'match', 'pattern' => '/[0-9]/'],
            [['imei1', 'imei2'], 'string', 'max' => 15],
            [['device_note', 'specification'], 'string', 'max' => 512],
            [['brand', 'model', 'sn', 'dt_title'], 'string', 'max' => 255],
            [['dev_comp', 'chekMode'], 'boolean'],
            [['device_mac'], 'match', 'pattern' => '/(^([A-F|a-f|0-9]{2}[:\-]){5}[A-F|a-f|0-9]{2}$)|' .
                '(^([A-F|a-f|0-9]{6}[:\-])[A-F|a-f|0-9]{6}$)|' .
                '(^([A-F|a-f|0-9]{4}\.){2}[A-F|a-f|0-9]{4}$)|' .
                '(^([A-F|a-f|0-9]{12})$)/', 'message' => 'Mac-адрес не соответствует формату'],
            ['device_mac', 'uniqueMac', 'on' => self::SCENARIO_INSERT]
        ];
    }

    public function scenarios(){
        $scenario = parent::scenarios();
        $scenario[self::SCENARIO_INSERT] = [
            'sn', 'imei1', 'workplace_id','type_id', 'parent_device_id', 'device_note', 'specification',
            'brand', 'model', 'dt_title', 'dev_comp', 'device_mac', 'chekMode'
        ];
        $scenario[self::SCENARIO_UPDATE] = [
            'sn', 'imei1', 'workplace_id','type_id', 'parent_device_id', 'device_note', 'specification',
            'brand', 'model', 'dt_title', 'dev_comp', 'device_mac', 'chekMode'
        ];

        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип устройства',
            'dt_title' => 'Тип Устр.',
            'device_note' => 'Заметка',
            'specification' => 'Спецификация',
            'workplace_id' => 'Рабочее место',
            'brand' => 'Бренд',
            'model' => 'Модель',
            'chekMode' => 'Серийный номер отсутствует',
            'sn' => 'Серийный номер',
            'branch_id' => 'Подразделение',
            'room_id' => 'Отдел/Кабинет',
            'count' => 'Количество',
            'parent_device_id' => 'Родитель',
            'dev_comp' => 'Компл.',
            'fake_device' => 'Фейк',
            'device_mac' => 'МАС-адрес'
        ];
    }

    /**
     * Валидатор проверяет уникальность мак-адреса
     * @param $attribute
     * @param $params
     */
    public function uniqueMac($attribute, $params){

        $mac = Netints::findAll(['mac' => $this->device_mac]);
        if ($mac) {
            $this->addError($attribute, 'Устройство с таким mac-адресом уже существует');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceType(){
        return $this->hasOne(DeviceType::className(), ['id' => 'type_id']);
    }

    /**
     * Связываем таблицу устройств с таблицей рабочих мест
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_id']);
    }

    public function getVoip(){
        return $this->hasMany(VoipNumbers::className(), ['device_id' => 'id']);
    }

    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('wp_owners', ['workplace_id' => 'workplace_id']);
    }

    public function getNetints(){
        return $this->hasMany(Netints::className(), ['device_id' => 'id']);
    }

    /**
     * Получить количество устройств (по ид) на рабочем месте (по ид)
     * @param $type_id
     * @param $id_wp
     * @return mixed
     */
    public function getCountOnWp($type_id, $id_wp){
        $count = Devices::find()->where(['type_id' => $type_id, 'workplace_id' => $id_wp])->count();
        return $count;
    }

    /**
     * @param $type_id
     * @param string $term значение вводимое в поле Бренд на форме
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayBrands($type_id, $term){

        return Devices::find()->select('brand as value, brand as label, COUNT(*) as count')
            ->where("brand > ''")
            ->andWhere(['type_id' => $type_id])
            ->andWhere(['like', 'LOWER(brand)', mb_strtolower($term)])
            ->groupBy('brand')->orderBy('count DESC')->asArray()->all();
    }

    /**
     * @param $type_id
     * @param string $term значение вводимое в поле Модель на форме
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayModels($type_id, $term){
        return Devices::find()->select('model as value, model as label, COUNT(*) as count')
            ->where("model > ''")
            ->andWhere(['type_id' => $type_id])
            ->andWhere(['like', 'LOWER(model)', mb_strtolower($term)])
            ->groupBy('model')->orderBy('count DESC')->asArray()->all();
    }

    /**
     * @param int $mode
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arraySns($mode = 1){
        if ($mode == 1) $select = 'sn as value, sn as label';
        else $select = 'sn';

        return Devices::find()->select($select)
            ->where("sn > ''")
            ->groupBy('sn')->orderBy('sn')->asArray()->all();
    }

    public static function arrayImei1(){
        return Devices::find()->select('imei1 as value, imei1 as label')
            ->where("imei1 > ''")
            ->groupBy('imei1')->orderBy('imei1')->asArray()->all();
    }

    public static function arrayImei2(){
        return Devices::find()->select('imei2 as value, imei2 as label')
            ->where("imei2 > ''")
            ->groupBy('imei2')->orderBy('imei2')->asArray()->all();
    }

    public static function arraySpecifications($type_id, $term){
        return Devices::find()->select('specification as value, specification as label, COUNT(*) as count')
            ->where("specification > ''")
            ->andWhere(['type_id' => $type_id])
            ->andWhere(['like', 'LOWER(specification)', mb_strtolower($term)])
            ->groupBy('specification')->orderBy('count DESC')->asArray()->all();
    }

    /**
     * Перемещаем устройство на другое рабочее место
     * вместе с его подчиненными устройствами
     * @param $id_wp
     * @return bool
     */
    public function setTowp($id_wp, $comment = null){
        /* @var $child Devices */
        $old_wp = $this->workplace_id;
        $this->workplace_id = $id_wp;
        if ($id_wp == 130)
            $this->parent_device_id = 0;
        //if ($id_wp == 131) $this->parent_device_id = null;
        if ($this->save()) {
            $children = Devices::find()->where(['parent_device_id' => $this->id])->all();
            foreach ($children as $child){
                $child->workplace_id = $id_wp;
                $child->save();
            }
            StoryDevice::addStory($id_wp, $this->id, StoryDevice::EVENT_IN, 'Перемещение с РМ №'. $old_wp . ' '.$comment);
            StoryDevice::addStory($old_wp, $this->id, StoryDevice::EVENT_OUT, 'Перемещение на РМ №'. $id_wp . ' '.$comment  );
            return true;
        }
        else return false;
    }

    /**
     * Облегченная функция работы с устройствами на рабочем месте
     * возвращает минимум информации
     * @param $id_wp
     * @return ActiveDataProvider
     */
    public static function getIdsOnwp($id_wp){
        $query1 = (new Query())
            ->select([
                'id' => 'id',
                'workplace_id' => 'workplace_id',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['workplace_id' => $id_wp])
            ->andWhere("parent_device_id IS NULL OR parent_device_id = 0");

        $queryId = (new Query())->select('id')->from('devices')->where(['workplace_id' => $id_wp])->all();
        $arrId = ArrayHelper::getColumn($queryId, 'id');

        $query2 = (new Query())
            ->select([
                'id' => 'id',
                'workplace_id' => 'workplace_id',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['IN', 'parent_device_id', $arrId]);

        $union = (new Query())
            ->select('id, workplace_id, parent_device_id')
            ->from(['tab' => $query1->union($query2)]);

        $provider = new ActiveDataProvider(['query' => $union, 'pagination' => false]);

        return $provider;
    }

    public static function findByMac($mac){
        /* @var $net Netints */
        $net = Netints::findOne(['mac' => $mac]);
        $dev = Devices::findOne($net->device_id);
        return $dev;
    }

}
