<?php

namespace backend\models;

use Yii;
//use yii\bootstrap\ActiveField;
//use yii\bootstrap\Dropdown;
//use yii\helpers\Html;
//use yii\web\JsExpression;

/**
 * Модель для таблицы "dt_enquiry_devices".
 * Документ "Заявка на оборудование"
 *
 * @property integer $dt_enquiries_id
 * @property integer $type_id
 * @property integer $parent_device_id
 * @property string $note
 * @property integer $id
 * @property integer $device_id
 * @property integer $dt_def_dev_id
 * @property integer $workplace_id
 * @property integer $status
 * @property string statusString
 */
class DtEnquiryDevices extends \yii\db\ActiveRecord
{
    const RESERVED = 1; //зарезервировано
    const NEED_BUY = 2; //требуется покупка
    const REQUEST_INVOICE = 3; //запросить счет
    const AWAITING_PAYMENT = 4; //ожидает оплаты
    const PAID = 5; //куплено

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
            self::PAID => 'Куплено',
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
                'device_id', 'dt_def_dev_id', 'workplace_id', 'status'], 'integer'],
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

        ];
    }
}
