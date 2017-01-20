<?php
/**
 * Модель "Устройства в Счете" таблица "dt_invoice_devices"
 * Отвечает за данные по устройствам, отраженным в документе "Счет" (DtInvoices)
 */

namespace backend\models;

use Yii;

/**
 * @property integer $id
 * @property integer $dt_invoices_id
 * @property string $price
 * @property integer $status имеет ту же расшифровку что и DtEnquiryDevices
 * @property string statusString
 * @property string $note
 * @property integer $type_id
 * @property integer $dt_enquiries_id
 * @property integer $dt_enquiry_devices_id
 * @property DtEnquiries dtEnquiry
 * @property DtEnquiryDevices dtEnquiryDevice
 */
class DtInvoiceDevices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_invoice_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_invoices_id'], 'required'],
            [['dt_invoices_id', 'status', 'type_id', 'dt_enquiries_id', 'dt_enquiry_devices_id'], 'integer'],
            [['price'], 'number'],
            [['note'], 'string', 'max' => 255],
        ];
    }

	/**
	 * Получаем строку статуса. Массив строк получаем из аналогичной модели "Устройства в заявке на оборудование"
	 * @return string
	 */
	public function getStatusString(){
		$arr = DtEnquiryDevices::arrStatusString();
		return $arr[$this->status];
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDtEnquiry() {
        return $this->hasOne(DtEnquiries::className(), ['id' => 'dt_enquiries_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDtEnquiryDevice() {
        return $this->hasOne(DtEnquiryDevices::className(), ['id' => 'dt_enquiry_devices_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_invoices_id' => '№ Док.',
            'type_id' => 'Тип устройства',
            'price' => 'Цена',
            'status' => 'Статус',
            'note' => 'Заметка',
            'dt_enquiries_id' => 'Док. Заявка'
        ];
    }
}
