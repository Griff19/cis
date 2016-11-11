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
 * @property integer summPay
 */
class DtInvoices extends \yii\db\ActiveRecord
{
    public $d_partners_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_date'], 'safe'],
            [['d_partners_id', 'delivery_type'], 'integer'],
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
     * @return mixed
     */
    public function getSummPay(){
        return DtInvoicesPayment::find()->where(['dt_invoices_id' => $this->id])->sum('summ');
    }
}
