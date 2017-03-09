<?php
/**
 * Модель документа "Счет", таблица "dt_invoices".
 * Содержит информацию по оплачиваемым устройствам и произведенным платежам
 */

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $doc_number
 * @property string $doc_date
 * @property integer $d_partners_id
 * @property integer $delivery_type
 * @property string $summ
 * @property string $d_partners_name Переменная для подстановки имени контрагента и определени ИД
 * @property integer $status
 * @property integer summPay
 * @property string statusString строка статуса документа
 * @property DtInvoiceDevices invoiceDevices
 * @property DPartners $partner
 * @property string docDate
 * @property string summary Краткая информация о счете
 */
class DtInvoices extends ActiveRecord
{
    const DOC_DEL = 0; //удаленный документ
    const DOC_NEW = 1; //новый, не сохраненный документ
    //const DOC_PROCESS = 2;
    const DOC_SAVE = 2; //сохраненный документ
    const DOC_CLOSED = 3; //счет закрыт

    public $d_partners_name; //для работы автоподстановки

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_invoices';
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->doc_date = Yii::$app->formatter->asDate($this->doc_date, 'yyyy-MM-dd');
            return true;
        }
        return false;
    }

    /**
     * Определяем строковые значения статуса
     * @return array
     */
    public static function arrStatusString()
    {
        return [
            self::DOC_DEL => 'Удален',
            self::DOC_NEW => 'Новый',
            self::DOC_SAVE => 'В&nbsp;Обработке',
            self::DOC_CLOSED => 'Закрыт'
        ];
    }

    /**
     * Возвращает строку статуса по номеру
     * @return mixed
     */
    public function getStatusString()
    {
        $arr = self::arrStatusString();
        return $arr[$this->status];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_date'], 'safe'],
            [['d_partners_id', 'delivery_type', 'status'], 'integer'],
            [['summ'], 'number'],
            [['doc_number'], 'string', 'max' => 10],
            ['d_partners_name', 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doc_number' => 'Док №',
            'doc_date' => 'Дата',
            'd_partners_id' => 'Контрагент',
            'd_partners_name' => 'Контрагент',
            'delivery_type' => 'Доставка',
            'summ' => 'Сумма',
            'summPay' => 'Оплачено',
            'status' => 'Статус',
            'statusString' => 'Статус',
        ];
    }

    /**
     * Форматируем дату
     * @return string
     */
    public function getDocDate()
    {
        return Yii::$app->formatter->asDate($this->doc_date);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceDevices()
    {
        return $this->hasMany(DtInvoiceDevices::className(), ['dt_invoices_id' => 'id']);
    }

    /**
     * Связь с моделью "Контрагенты"
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(DPartners::className(), ['id' => 'd_partners_id']);
    }

    /**
     * Сумма всех оплат по счету
     * @return mixed
     */
    public function getSummPay()
    {
        return DtInvoicesPayment::find()->where(['dt_invoices_id' => $this->id, 'status' => DtInvoicesPayment::PAY_OK])->sum('summ');
    }

    /**
     * Краткая информация о счете
     * @return string
     */
    public function getSummary()
    {
        return 'Счет ' . $this->id . ' №' . $this->doc_number . ' от ' . $this->docDate;
    }

    /**
     * "Сохраняем" документ изменяя все связанные статусы
     * @return bool
     */
    public function saveDoc()
    {

        if ($this->summ > $this->summPay) {
            return false;
        } else {
            /** @var DtInvoiceDevices[] $did_models устройства в документе "Счет" */
            $did_models = DtInvoiceDevices::findAll(['dt_invoices_id' => $this->id]);
            foreach ($did_models as $did_model) {
                DtEnquiryDevices::updateAll(['status' => DtEnquiryDevices::PAID], ['id' => $did_model->dt_enquiry_devices_id]);
            }
            DtInvoiceDevices::updateAll(['status' => DtEnquiryDevices::PAID], ['dt_invoices_id' => $this->id]);
            $this->status = DtInvoices::DOC_CLOSED;
            $this->save();
        }
        return true;
    }
}
