<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "dt_invoices_payment".
 *
 * @property integer $id
 * @property integer $dt_invoices_id
 * @property string $agreed_date
 * @property string $summ
 * @property integer $employee_id
 * @property string $employee_name
 * @property DtInvoices dtInvoice
 * @property Employees employee
 */
class DtInvoicesPayment extends \yii\db\ActiveRecord
{
    public $employee_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_invoices_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_invoices_id', 'employee_id'], 'integer'],
            [['agreed_date'], 'date'],
            [['summ'], 'number'],
            ['employee_name', 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_invoices_id' => 'Док №',
            'agreed_date' => 'Согласование',
            'summ' => 'Сумма',
            'employee_id' => 'Сотрудник',
            'employee_name' => 'Сотрудник'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDtInvoice() {
        return $this->hasOne(DtInvoices::className(), ['id' => 'dt_invoices_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee() {
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }
}
