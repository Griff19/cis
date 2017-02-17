<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use DateTime;

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
 * @property \backend\models\Employees employee
 * @property string statusString
 * @property mixed enquiryDevices
 * @property mixed invoices
 */
class DtEnquiries extends ActiveRecord
{
    const DTE_NEW = 0; //новый документ
    const DTE_SAVED = 1; //сохраненный документ
    const DTE_COMPLETE = 2; //документ обработан
    const DTE_CLOSED = 3; //документ закрыт

    public $employee_name; //используем для автоподстановки и сортировки

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->create_date = Yii::$app->formatter->asDate($this->create_date, 'yyyy-MM-dd');
        $this->do_date = Yii::$app->formatter->asDate($this->do_date, 'yyyy-MM-dd');

        return parent::beforeSave($insert);
    }

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
            'employee_id' => 'Сотрудник',
            'create_date' => 'Дата создания',
            'createDate' => 'Дата создания',
            'do_date' => 'Исполнить до',
            'doDate' => 'Исполнить до',
            'create_time' => 'Время создания',
            'memo' => 'Есть служебка',
            'status' => 'Статус',
            'statusString' => 'Статус',
        ];
    }

    /**
     * Форматируем дату создания документа
     * @return string
     */
    public function getCreateDate(){
        return Yii::$app->formatter->asDate($this->create_date);
    }

    /**
     * Форматируем дату исполнения документа
     * @return string
     */
    public function getDoDate(){
        return Yii::$app->formatter->asDate($this->do_date);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    public function getOwnerWP(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])->viaTable('wp_owners', ['workplace_id' => 'workplace_id']);
    }

    public function getWorkplaces(){
        return $this->hasMany(Workplaces::className(), ['id' => 'workplace_id'])->viaTable('dt_enquiry_workplaces', ['dt_enquiries_id' => 'id']);
    }

    /**
     * Связь с таблицей устройств
     * @return \yii\db\ActiveQuery
     */
    public function getEnquiryDevices(){
        return $this->hasMany(DtEnquiryDevices::className(), ['dt_enquiries_id' => 'id']);
    }

    /**
     * Связь с документом "Счет"
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices(){
        return $this->hasMany(DtInvoices::className(), ['id' => 'dt_inv_id'])->via('enquiryDevices');
    }

}
