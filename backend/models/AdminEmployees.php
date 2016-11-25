<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * Модель для отображения данных о пользователе на админской станице
 * @property integer $id
 * @property string $fio
 * @property string $cell_number
 * @property string $email
 * @property string $job_title
 */
class AdminEmployees extends \yii\db\ActiveRecord
{

    public $fio;
    public $cell_number;
    public $email;
    //public $job_title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['fio', 'email', 'job_title'], 'string', 'max' => 255],
            ['cell_number', 'string', 'max' => 12]
        ];
    }

     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'cell_number' => 'Моб.номер',
            'email' => 'Email',
            'job_title' => 'Должность'
        ];
    }

    /**
     * Готовим массив состоящий из ФИО сотрудников
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayFios(){
        return Employees::find()->select('snp as value, snp as label')
            ->where("snp > ''")->andWhere('status > 0')->orderBy('snp')->asArray()->all();
    }
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayCells(){
        return CellNumbers::find()->select('cell_number as value, cell_number as label')
            ->where("cell_number > '' AND status = 1")->orderBy('cell_number')->asArray()->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrayEmails(){
        return Emails::find()->select('email_address as value, email_address as label')
            ->where("email_address > '' AND status = 1")->orderBy('email_address')->asArray()->all();
    }

    /**
     * @param $employee_id
     * @return ActiveDataProvider
     */
    public static function workplacesProvider($employee_id) {
        $query = (new Query())
            ->select([
                'branch_title' => 'branches.branch_title',
                'id' => 'workplaces.id',
                'status' => 'wp_owners.status',
                'room_title' => 'rooms.room_title',
                'workplaces_title' => 'workplaces.workplaces_title',
                'voip_number' => 'voip_numbers.voip_number',
                'device_type_title' => 'device_type.title',
                'devices_id' => 'devices.id',
                'devices_device_note' => 'devices.device_note',
                'sub_type_title' => 'sub_type.title',
                'sub_devices_parent_id' => 'sub_devices.parent_device_id',
                'netints_id' => 'netints.id',
                'netints_type' => 'netints.type',
                'ip' => 'netints.ipaddr',
                'mac' => 'netints.mac',
                'domain_name' => 'netints.domain_name',
                'ports' => 'netints.port_count'
            ])
            ->from('workplaces')
            ->leftJoin('branches', 'branches.id = workplaces.branch_id')
            ->leftJoin('rooms', 'rooms.id = workplaces.room_id')
            ->leftJoin('wp_owners', 'wp_owners.workplace_id = workplaces.id')
            ->leftJoin('devices', 'devices.workplace_id = workplaces.id')
            ->leftJoin('device_type', 'device_type.id = devices.type_id')
            ->leftJoin(['sub_devices' => 'devices'], 'sub_devices.parent_device_id = devices.id')
            ->leftJoin(['sub_type' => 'device_type'], 'sub_type.id = sub_devices.type_id')
            ->leftJoin('netints', 'netints.device_id = devices.id OR netints.device_id = sub_devices.id')
            ->leftJoin('voip_numbers', 'voip_numbers.workplace_id = workplaces.id AND voip_numbers.status = 1')
            ->leftJoin('employees', 'employees.id = wp_owners.employee_id')
            //->leftJoin('cell_numbers', 'cell_numbers.employee_id = employees.id AND cell_numbers.status = 1')
            //->leftJoin('emails', 'emails.employee_id = employees.id AND emails.status = 1')
            ->where('workplaces.id <> 119')// AND netints.id > 0')
            ->andWhere(['employees.id' => $employee_id])
            ->orderBy('workplaces.id')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $dataProvider;
    }

}
