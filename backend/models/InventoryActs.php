<?php

namespace backend\models;

use Yii;

/**
 * Модель документа Акт инвентаризации
 * @property integer $id
 * @property integer $workplace_id
 * @property integer $owner_employee_id
 * @property integer $exec_employee_id
 * @property string $act_date
 * @property string $curr_date
 * @property integer $status
 * @property string $comm
 * @property string $employee_name
 * @property string $owner_name
 * @property mixed employee
 * @property Employees ownerEmployee
 * @property Employees execEmployee
 * @property Workplaces workplace
 */
class InventoryActs extends \yii\db\ActiveRecord
{
    const DEVICE_OK = 'Все ОК!';
    const MISSING_DEV = 'Пропавшее Устройство';
    const REPLACE_DEV = 'Замена Устройства';
    const ADDITION_DEV = 'Новое Устройство';

    const DOC_NEW = 0; //новый документ
    const DOC_SAVED = 1; //сохраненный документ
    const DOC_PRINTED = 2; //распечатанный документ
    const DOC_AGREE = 3; //подтвержденный документ

    public $employee_name; //имя сотрудника, проводящего инвентаризацию
    public $owner_name; //имя владельца рабочего места, ответственный
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inventory_acts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplace_id', 'owner_employee_id', 'exec_employee_id'], 'required'],
            [['workplace_id', 'owner_employee_id', 'exec_employee_id', 'status'], 'integer'],
            [['act_date', 'curr_date'], 'safe'],
            [['comm', 'employee_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workplace_id' => 'Рабочее место №',
            'owner_name' => 'Ответственный',
            'owner_employee_id' => 'Ответственный за рабочее место',
            'employee_name' => 'Сотрудник Ревизор',
            'exec_employee_id' => 'Ид Ревизора',
            'act_date' => 'Дата Акта',
            'curr_date' => 'Дата создания',
            'status' => 'Статус',
            'comm' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnerEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'owner_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'exec_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_id']);
    }

    /**
     * Получаем массив статусов утройтв в таблце акта инвентаризации. Ключи элементов = id устройств
     * @return array|\yii\db\ActiveRecord[]
     */
    public function arrayDevIDinTb(){
        $arrId = [];
        $arrs = InventoryActsTb::find()->select('device_id, status')->where(['act_id' => $this->id])
            ->orderBy('device_id')->asArray()->all();
        foreach ($arrs as $arr){
            $arrId[$arr['device_id']] =  $arr['status'];
        }
        return $arrId;
    }

   public function getActsTable(){
       return $this->hasMany(InventoryActsTb::className(), ['act_id' => 'id'])
           ->orderBy('device_id');
   }

    /**
     * @param $id_wp
     * @return $this
     */
    public function getLastAct($id_wp){
        return $this->find()
            ->where(['workplace_id' => $id_wp])
            ->andWhere(['status' => self::DOC_AGREE])
            ->orderBy(['act_date' => SORT_DESC])
            ->limit(1)
            ->one();
    }

}
