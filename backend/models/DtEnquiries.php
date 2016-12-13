<?php

namespace backend\models;

use Yii;

/**
 * Модель документа "Заявка на оборудование", соответствует таблице "dt_enquiries".
 *
 * @property integer id
 * @property integer employee_id
 * @property string employee_name
 * @property string create_date
 * @property string do_date
 * @property string create_time
 * @property mixed memo
 * @property integer status
 * @property Employees employee
 * @property string statusString
 */
class DtEnquiries extends \yii\db\ActiveRecord
{
    const DTE_NEW = 0; //новый документ
    const DTE_SAVED = 1; //сохраненный документ
    const DTE_COMPLETE = 2; //документ обработан
    const DTE_CLOSED = 3; //документ закрыт

    public $employee_name; //используем для автоподстановки и сортировки

    /**
     * @return array
     */
    public static function arrStatusString(){
        return [
            self::DTE_NEW => 'Новый',
            self::DTE_SAVED => 'В обработке',
            self::DTE_COMPLETE => 'Обработан',
            self::DTE_CLOSED => 'Закрыт'
        ];
    }

    /**
     * @return string
     */
    public function getStatusString(){
        $arr = self::arrStatusString();
        return $arr[$this->status];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_enquiries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'status'], 'integer'],

            ['memo', 'boolean'],
            [['create_date', 'do_date', 'create_time'], 'safe'],
            ['do_date', 'compare', 'compareAttribute' => 'create_date', 'operator' => '>', 'message' => '"{attribute}" должна быть позже "{compareAttribute}"']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_name' => 'Имя сотрудника',
            'employee_id' => 'Ид Сотрудника',
            'create_date' => 'Дата создания',
            'do_date' => 'Исполнить до',
            'create_time' => 'Время создания',
            //'workplace_id' => 'Номер рабочего места',
            'memo' => 'Есть служебка',
            'status' => 'Статус',
            'statusString' => 'Статус',
        ];
    }

    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    public function getOwnerWP(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('wp_owners', ['workplace_id' => 'workplace_id']);
    }

    public function getWorkplaces(){
        return $this->hasMany(Workplaces::className(), ['id' => 'workplace_id'])->viaTable('dt_enquiry_workplaces', ['dt_enquiries_id' => 'id']);
    }

}
