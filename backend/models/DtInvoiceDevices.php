<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "dt_invoice_devices".
 *
 * @property integer $id
 * @property integer $dt_invoices_id
 * @property string $price
 * @property integer $status
 * @property string $note
 * @property integer $type_id
 * @property integer $dt_enquiries_id
 * @property integer dt_enquiry_devices_id
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
     * @return \yii\db\ActiveQuery
     */
    public function getDtEnquiry() {
        return $this->hasOne(DtEnquiries::className(), ['id' => 'dt_enquiries_id']);
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
