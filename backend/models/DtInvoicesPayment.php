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
 * @property integer $status
 * @property DtInvoices dtInvoice
 * @property Employees employee
 * @property mixed agreedDate
 */
class DtInvoicesPayment extends \yii\db\ActiveRecord
{
    const PAY_DELETE = 0; //удален
    const PAY_WAITING = 1; //ожидает согласования
    const PAY_AGREED = 2; //согласован
    const PAY_REFER = 3; //передан бухгалтеру
    const PAY_OK = 4; //оплата прошла

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
            [['dt_invoices_id', 'employee_id', 'status'], 'integer'],
            [['agreed_date'], 'date'],
            [['summ'], 'number'],
            ['employee_name', 'string', 'max' => 255]
        ];
    }

	public function scenarios(){
		$scenario = parent::scenarios();
		$scenario['insert'] = ['dt_invoices_id', 'employee_id', 'status', 'agreed_date', 'summ', 'employee_name'];
		$scenario['update'] = ['dt_invoices_id', 'employee_id', 'status', 'summ', 'employee_name'];
		return $scenario;
	}

	/**
	 * Перед сохранением меняем формат даты для хранения в базе
	 * @param bool $insert
	 * @return bool
	 * @internal param bool $insert
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->agreed_date = (new \DateTime($this->agreed_date))->format('Y-m-d');

			return true;
		} else {
			return false;
		}
	}

    /**
     * @return array
     */
    public static function arrStatusString(){
        return [
            self::PAY_DELETE => 'Удален',
            self::PAY_WAITING => 'Согласовывается',
            self::PAY_AGREED => 'Согласован',
            self::PAY_REFER => 'Оплачивается',
            self::PAY_OK => 'Оплачен'
        ];
    }

    /**
     * @return string
     */
    public function getStatusString(){
        $arr = self::arrStatusString();
        return $arr[$this->status];
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
            'employee_name' => 'Сотрудник',
            'status' => 'Статус',
            'statusString' => 'Статус'
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
