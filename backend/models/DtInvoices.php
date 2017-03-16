<?php
/**
 * Модель документа "Счет", таблица "dt_invoices".
 * Содержит информацию по оплачиваемым устройствам и произведенным платежам
 */

namespace backend\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * @property integer $id
 * @property string $doc_number                 Номер документа
 * @property string $doc_date                   Дата документа
 * @property integer $d_partners_id             Идентификатор контрагента
 * @property integer $delivery_type             Доставка, тип доставки
 * @property string $summ                       Сумма счета
 * @property string $d_partners_name            Переменная для подстановки имени контрагента и определени ИД
 * @property integer $status                    Статус документа
 * @property integer summPay                    Сумма всех платежей по счету
 * @property string statusString                Строка статуса документа
 * @property DtInvoiceDevices invoiceDevices    "Табличная часть" документа "Счет"
 * @property DPartners $partner                 Связь с моделью "Контрагенты"
 * @property string docDate                     Форматированная дата документа
 * @property string summary                     Краткая информация о счете
 * @property ActiveQuery enquiries              Связь с моделью "Заявка на оборудование" через промежуточную таблицу
 */
class DtInvoices extends ActiveRecord
{
    const DOC_DEL = 0; //удаленный документ
    const DOC_NEW = 1; //новый, не сохраненный документ
    const DOC_WAITING_AGREE = 2; //ожидает согласования
    const DOC_AWAITING_PAYMENT = 3; //ожидает оплаты
    const DOC_SAVE = 4; //документ оплачен
    const DOC_CLOSED = 5; //счет закрыт


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
            self::DOC_WAITING_AGREE => 'Согласовывается',
            self::DOC_AWAITING_PAYMENT => 'Ожидает оплаты',
            self::DOC_SAVE => 'Оплачен',
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
        $str = $arr[$this->status];
        $str = str_replace(' ', '&nbsp;', $str);
        return $str;
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
     * Связь с документом "Заявка на оборудование" через промежуточную таблицу
     * @return \yii\db\ActiveQuery
     */
    public function getEnquiries()
    {
        return $this->hasMany(DtInvoices::className(), ['id' => 'enquiry_id'])
            ->viaTable('dt_enquiry_invoice', ['invoice_id' => 'id']);
    }

    /**
     * @param $enquiry_id
     */
    public function setEnquiries($enquiry_id)
    {
        $dt_enquiry_invoice = new DtEnquiryInvoice();
        $dt_enquiry_invoice->enquiry_id = $enquiry_id;
        $dt_enquiry_invoice->invoice_id = $this->id;
        $dt_enquiry_invoice->save();
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
