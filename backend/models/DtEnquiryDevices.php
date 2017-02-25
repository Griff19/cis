<?php
/**
 * Модель "Устройства в заявке на оборудование" таблица "dt_enquiry_devices"
 * Отвечает за данные по устройствам, отраженным в документе "Заявка на оборудование" (DtEnquiries)
 */

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * @property integer $dt_enquiries_id
 * @property integer $type_id
 * @property integer $parent_device_id
 * @property string $note
 * @property integer $id
 * @property integer $device_id
 * @property integer $dt_def_dev_id
 * @property integer $workplace_id
 * @property integer $status
 * @property integer $dt_inv_id идентификатор документа "Счет"
 * @property string statusString
 * @property DtEnquiries dtEnquiry
 */
class DtEnquiryDevices extends ActiveRecord
{
    const RESERVED = 1; //зарезервировано
    const NEED_BUY = 2; //требуется покупка
    const REQUEST_INVOICE = 3; //запросить счет
    const WAITING_AGREE = 4; //ожидает согласования
    const AWAITING_PAYMENT = 5; //ожидает оплаты
    const PAID = 6; //куплено
    const DEBIT = 7; //приходовано

    /**
     * Готовим массив строк статуса
     * @return array
     */
    public static function arrStatusString(){
        return [
            self::RESERVED => 'Зарезервировано',
            self::NEED_BUY => 'Требуется покупка',
            self::REQUEST_INVOICE => 'Запросить счет',
            self::AWAITING_PAYMENT => 'Ожидает оплаты',
            self::WAITING_AGREE => 'Ожидает согласования',
            self::PAID => 'Куплено',
            self::DEBIT => 'Оприходовано'
        ];
    }

    /**
     * Получаем строку статуса из массива
     * @return mixed
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
        return 'dt_enquiry_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_enquiries_id', 'type_id'], 'required'],
            [['dt_enquiries_id', 'type_id', 'parent_device_id',
                'device_id', 'dt_def_dev_id', 'workplace_id', 'dt_inv_id', 'status'], 'integer'],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dt_enquiries_id' => 'Номер документа Заявки',
            'type_id' => 'Тип устройства',
            'parent_device_id' => 'Родительское устройство',
            'note' => 'Заметка',
            'id' => 'ИД',
            'device_id' => 'Ид уст.',
            'dt_def_dev_id' => 'Ид строки',
            'workplace_id' => 'РМ №',
            'status' => 'Статус',
            'statusString' => 'Статус',
            'dt_inv_id' => 'Ид счета'
        ];
    }

    /**
     * Связываем с моделью документа "Заявка на оборудование"
     * @return \yii\db\ActiveQuery
     */
    public function getDtEnquiry() {
        return $this->hasOne(DtEnquiries::className(), ['id' => 'dt_enquiries_id']);
    }
}
