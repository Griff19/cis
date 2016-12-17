<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "dt_invoices".
 * Документ "Счет"
 *
 * @property integer $id
 * @property string $doc_number
 * @property string $doc_date
 * @property integer $d_partners_id
 * @property integer $delivery_type
 * @property string $summ
 * @property string $d_partners_name переменная для подстановки имени контрагента и определени ИД
 * @property integer $status
 * @property integer summPay
 * @property string statusString строка статуса документа
 */
class DtInvoices extends \yii\db\ActiveRecord
{
    const DOC_DEL = 0; //удаленный документ
    const DOC_NEW = 1; //новый, не сохраненный документ
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
     * Определяем строковые значения статуса
     * @return array
     */
    public static function arrStatusString(){
        return [
            self::DOC_DEL => 'Удален',
            self::DOC_NEW => 'Новый',
            self::DOC_SAVE => 'Сохранен',
            self::DOC_CLOSED => 'Закрыт'
        ];
    }

    /**
     * Возвращает строку статуса по номеру
     * @return mixed
     */
    public function getStatusString() {
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
            'summPay' => 'Сумма оплаты',
            'status' => 'Статус'
        ];
    }

    /**
     * Связанная модель контрагентов
     * @return \yii\db\ActiveQuery
     */
    public function getPartner(){
        return $this->hasOne(DPartners::className(), ['id' => 'd_partners_id']);
    }

    /**
     * Сумма всех оплат по счету
     * @return mixed
     */
    public function getSummPay(){
        return DtInvoicesPayment::find()->where(['dt_invoices_id' => $this->id, 'status' => DtInvoicesPayment::PAY_OK])->sum('summ');
    }
}
