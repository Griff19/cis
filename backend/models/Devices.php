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
 * @property string fullWorkplace полное имя рабочего места
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
            'sn', 'imei1', 'imei2', 'workplace_id','type_id', 'parent_device_id', 'device_note', 'specification',
            'brand', 'model', 'dt_title', 'dev_comp', 'device_mac', 'chekMode'
        ];
        $scenario[self::SCENARIO_UPDATE] = [
            'sn', 'imei1', 'imei2', 'workplace_id','type_id', 'parent_device_id', 'device_note', 'specification',
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

    /**
     * Возвращает полное наименование рабочего места
     * $mode = 0 - для вывода в строку, 1 - для вывода в "столбец"
     * @return string
     */
    public function getFullWorkplace($mode = 0){
        if ($mode == 0)
            return $this->workplace->room->branch->branch_title . ' - '
            . $this->workplace->room->room_title . ' - '
            . $this->workplace->workplaces_title;
        else
            return $this->workplace->room->branch->branch_title . "\n"
            . $this->workplace->room->room_title . "\n"
            . $this->workplace->workplaces_title . "\n"
            . $this->snp;
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
        $arr = Devices::find()->select('brand as value, brand as label, COUNT(*) as count')
            ->where("brand > ''")
            ->andWhere(['type_id' => $type_id])
            ->groupBy('brand')->orderBy('count DESC');
        if ($term != ' ') $arr->andWhere(['like', 'LOWER(brand)', mb_strtolower($term)]);
        return $arr->asArray()->all();
    }

    public static function arrBrands($type_id, $term){
        $query1 = (new Query)->select(['brand' => 'brand', 'count' => 'COUNT(*)', 'sort' => 'MAX(0)'])
            ->from("devices")
            ->where("brand > ''")
            ->andWhere(['type_id' => $type_id])
            //->andWhere(['ilike', 'brand', $term])
            ->groupBy("brand");

        $query2 = (new Query)->select(['brand' => 'brand', 'count' => 'COUNT(*)', 'sort' => 'MAX(1)'])
            ->from("devices")
            ->where("brand > ''")
            ->andWhere(['not in', 'brand', $query1->column()])
            //->andWhere(['ilike', 'brand', $term])
            ->groupBy("brand");

        if ($term != ' ') {
            $query1->andWhere(['ilike', 'brand', $term]);
            $query2->andWhere(['ilike', 'brand', $term]);
        }
        $union = (new Query())->select("brand, count, sort")
            ->from(['t' => $query1->union($query2)])
            ->orderBy(['sort' => SORT_ASC, 'count' => SORT_DESC])
            ->all();

        foreach ($union as $item){
            $arr[] = ['value' => $item['brand'], 'label' => $item['brand'], 'sort' => $item['sort']];
        }
        return $arr;
    }

    /**
     * @param $type_id
     * @param string $brand значение выбранное или введенное в поле "Бренд"
     * @param string $term значение вводимое в поле "Модель" на форме
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayModels($type_id, $brand, $term){
        $arr = Devices::find()->select('model as value, model as label, COUNT(*) as count')
            ->where("model > ''")
            ->andWhere(['type_id' => $type_id])
            ->groupBy('model')->orderBy('count DESC');
        if ($brand) $arr->andWhere(['brand' => $brand]);
        if ($term != ' ') $arr->andWhere(['like', 'LOWER(model)', mb_strtolower($term)]);
        return $arr->asArray()->all();;
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

    /**
     * Функция возвращает массив "Спецификаций", вызывается при ручном вводе
     * @param $type_id
     * @param $term
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arraySpecifications($type_id, $term){
        return Devices::find()->select('specification as value, specification as label, COUNT(*) as count')
            ->where("specification > ''")
            ->andWhere(['type_id' => $type_id])
            ->andWhere(['like', 'LOWER(specification)', mb_strtolower($term)])
            ->groupBy('specification')->orderBy('count DESC')->asArray()->all();
    }

    /**
     * Функция возвращает строку "спецификации"
     * @param $type_id
     * @param $brand
     * @param $model
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function arraySpecificationsAuto($type_id, $brand, $model){
        $arr = Devices::find()->select('specification')
            ->where(['type_id' => $type_id])
            ->andWhere(['brand' => $brand])
            ->andWhere(['model' => $model])
            ->andWhere("specification > ' '")
            ->orderBy('id DESC')
            ->asArray()->one();
        return $arr['specification'];

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

    /**
     * @param $id_wp
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayParentId($id_wp){

        $query = (new Query())
            ->select('parent_device_id as id')
            ->from('devices')
            ->where(['workplace_id' => $id_wp])
            ->andWhere('parent_device_id > 0')
            ->groupBy('parent_device_id')
            ->all();
        foreach ($query as $item) {
            $arr[] = $item['id'];
        }

        return !empty($arr) ? $arr : [0];
    }

}
