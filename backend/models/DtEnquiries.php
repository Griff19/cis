<?php
/**
 * Модель документа "Заявка на оборудование", соответствует таблице "dt_enquiries"
 */
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer id
 * @property integer employee_id                   Идентификатор сотрудника - используется в базе
 * @property string employee_name                  Имя сотрудника - используется в формах
 * @property string create_date                    Дата написания заявки, если нет служебки то дата создания
 * @property string do_date                        Исполнить заявку до
 * @property string create_time                    Дата и время создания заявки в базе
 * @property mixed memo                            Флаг наличия служебной записки
 * @property integer status                        Статус документа
 * @property integer dt_invoices_id                Идентификатор документа "Счет"
 * @property \backend\models\Employees employee    Связанная модель сотрудника
 * @property string statusString                   Строка статуса
 * @property mixed enquiryDevices                  "Табличная часть" документа "Заявка на оборудование"
 * @property mixed invoices                        Связанная модель документа "Счет"
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
        if ($this->do_date)
            $this->do_date = Yii::$app->formatter->asDate($this->do_date, 'yyyy-MM-dd');

        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public static function arrStatusString()
    {
        return [
            self::DTE_NEW => 'Новый',
            self::DTE_SAVED => 'В Обработке',
            self::DTE_COMPLETE => 'Обработан',
            self::DTE_CLOSED => 'Закрыт'
        ];
    }

    /**
     * Получаем строку статуса
     * @return string
     */
    public function getStatusString()
    {
        $arr = self::arrStatusString();
        $str = $arr[$this->status];
        $str = str_replace(' ', '&nbsp;', $str);
        return $str;
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
            ['employee_name', 'string'],
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
            'employee_name' => 'Имя заявителя',
            'employee_id' => 'Сотрудник',
            'create_date' => 'Дата создания',
            'createDate' => 'Дата создания',
            'do_date' => 'Исполнить до',
            'doDate' => 'Исполнить до',
            'create_time' => 'Время создания',
            'memo' => 'Есть служебка',
            'status' => 'Статус',
            'statusString' => 'Статус',
            'dt_invoices_id' => 'Счет',
        ];
    }

    /**
     * Форматируем дату создания документа
     * @return string
     */
    public function getCreateDate()
    {
        return Yii::$app->formatter->asDate($this->create_date);
    }

    /**
     * Форматируем дату исполнения документа
     * @return string
     */
    public function getDoDate()
    {
        return Yii::$app->formatter->asDate($this->do_date);
    }

    /**
     * Связь с сотрудником
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    /**
     * Связь с владельцем рабочего места
     * @return \yii\db\ActiveQuery
     */
    public function getOwnerWP()
    {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])
            ->viaTable('wp_owners', ['workplace_id' => 'workplace_id']);
    }

    /**
     * Связь с рабочим местом
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplaces()
    {
        return $this->hasMany(Workplaces::className(), ['id' => 'workplace_id'])
            ->viaTable('dt_enquiry_workplaces', ['dt_enquiries_id' => 'id']);
    }

    /**
     * Связь с таблицей устройств
     * @return \yii\db\ActiveQuery
     */
    public function getEnquiryDevices()
    {
        return $this->hasMany(DtEnquiryDevices::className(), ['dt_enquiries_id' => 'id']);
    }

    /**
     * Связь с документом "Счет" через промежуточную таблицу
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(DtInvoices::className(), ['id' => 'invoice_id'])
            ->viaTable('dt_enquiry_invoice', ['enquiry_id' => 'id']);
    }

}
