<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class StartEmployees
 * @package backend\models
 * @property string $snp
 * @property integer $emp_id
 * @property string $job_title
 * @property string $cell_number
 * @property string $voip_number
 * @property string $email_address
 * @property string $room_title
 * @property string $workplace_title
 * @property integer $wp_id
 */
class StartEmployees extends \yii\db\ActiveRecord
{
    public $branch_title;
//    public $snp;
    public $emp_id;
//    public $job_title;
    public $cell_number;
    public $voip_number;
    public $email_address;
    public $room_title;
    public $workplaces_title;
    public $wp_id;

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
            [['branch_title', 'snp', 'job_title', 'email_address', 'room_title', 'workplaces_title'], 'string', 'max' => 255],
            [['cell_number'], 'string', 'max' => 12],
            [['voip_number'], 'integer']
        ];
    }

     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'snp'=> 'Ф.И.О.',
            'job_title' => 'Должность',
            'branch_title' => 'Филиал',
            'cell_number' => 'Моб.телефон',
            'voip_number' => 'Вн.Тел.',
            'email_address' =>  'Адрес эл.почты',
            'room_title' => 'Помещение',
            'workplaces_title' => 'Раб.место'
        ];
    }

//    public function getBranch(){
//        return $this->hasOne(Branches::className(), ['id' => 'branch_id']);
//    }
//    /**
//     * @return $this
//     */
//    public function getWorkplace(){
//        return $this->hasMany(Workplaces::className(), ['id' => 'workplace_id'])
//            ->viaTable('wp_owners', ['employee_id' => 'id']);
//    }
    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public function getVoipProvider($id) {
    $query = $this->find()
            ->select("snp, voip_number")
            ->from("employees")
            ->leftJoin('wp_owners', 'employees.id = wp_owners.employee_id')
            ->leftJoin('workplaces', 'workplaces.id = wp_owners.workplace_id')
            ->leftJoin('devices', 'devices.workplace_id = workplaces.id AND devices.type_id = 3')
            ->leftJoin('voip_numbers', 'voip_numbers.device_id = devices.id')
            ->where(['employees.id' => $id])
            ;

        $provider = new ActiveDataProvider(['query' => $query]);
        return $provider;
    }


}