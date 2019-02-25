<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "phone_bill".
 *
 * @property int $id
 * @property string $number Номер телефона
 * @property string $date Дата счета
 * @property double $subscription Абонентская
 * @property double $one_time Разовые
 * @property double $online В сети
 * @property double $roaming Роуминг
 * @property double $cost Итого
 */
class PhoneBill extends \yii\db\ActiveRecord
{
    public $employee_snp;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone_bill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'date'], 'required'],
            [['date', 'employee_snp'], 'safe'],
            [['subscription', 'one_time', 'online', 'roaming', 'cost'], 'number'],
            [['number'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_snp' => 'Владелец',
            'number' => 'Номер',
            'date' => 'Дата',
            'subscription' => 'Абонентская',
            'one_time' => 'Разовые',
            'online' => 'В сети',
            'roaming' => 'В роуминге',
            'cost' => 'Итого',
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['id' => 'employee_id'])->viaTable('cell_numbers', ['cell_number' => 'number']);
    }
    
}
