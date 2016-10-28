<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "employees".
 *
 * @property integer $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $job_title
 * @property string $employee_number
 * @property string $unique_1c_number
 * @property integer $branch_id
 * @property string $snp
 * @property mixed status
 * @property string statusStr
 */
class Employees extends \yii\db\ActiveRecord
{
    const DISMISSED = 0;
    const ACTIVE = 1;

    public $file;
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
            [['branch_id'], 'required' , 'on' => 'create'],
            [['branch_id', 'status'], 'integer'],
            [['file'], 'file'],
            ['employee_number', 'string', 'max' => 10],
            ['unique_1c_number', 'string', 'max' => 36],
            [['surname', 'name', 'patronymic', 'snp', 'job_title'], 'string', 'max' => 255]
        ];
    }

     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'job_title' => 'Профессия/Должность',
            'employee_number' => 'Табельный номер',
            'unique_1c_number' => 'Код 1С',
            'branch_id' => 'Подразделение',
            'snp'=> 'Ф.И.О.',
            'file' => 'файл',
            'status' => 'Статус',
            'statusStr' => 'Статус'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch(){
        return $this->hasOne(Branches::className(), ['id' => 'branch_id']);
    }

    /**
     * @return $this
     */
    public function getWorkplace(){
        return $this->hasMany(Workplaces::className(), ['id' => 'workplace_id'])->viaTable('wp_owners', ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCellNumbers() {
        return $this->hasMany(CellNumbers::className(), ['employee_id' => 'id']);
    }

    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public function getVoipProvider($id) {
        $query = (new Query())
            ->select("snp, voip_number, workplaces.workplaces_title AS workplaces_title, voip_numbers.workplace_id AS workplace_id")
            ->from("employees")
            ->leftJoin('wp_owners', 'employees.id = wp_owners.employee_id')
            ->leftJoin('workplaces', 'workplaces.id = wp_owners.workplace_id')
            //->leftJoin('devices', 'devices.workplace_id = workplaces.id AND devices.type_id = 3')
            ->leftJoin('voip_numbers', 'voip_numbers.workplace_id = workplaces.id')
            ->where(['employees.id' => $id])
            ->andWhere("voip_number > 0")
        ;

        $provider = new ActiveDataProvider(['query' => $query]);
        //var_dump($provider);
        //die;
        return $provider;
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['employee_id' => 'id']);
    }
    /**
     * Формируем массив с данными по сотруднику для выпадающего списка
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arraySnp(){
        return Employees::find()->select("('[' || id || '] ' || snp) as value, snp || ' (' || job_title || ') ' as label")
            ->where("snp > ''")->andWhere('status > 0')->orderBy('snp')->asArray()->all();
    }

    /**
     * Формируем массив с ФИО и ИД сотрудника для автоподстановки
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arraySnpId(){
        return Employees::find()->select("snp as value, snp as label, id as id")
            ->where("snp > ''")->andWhere('status > 0')->orderBy('snp')->asArray()->all();
    }

    /**
     * Привязываем модель "почтовых адресов"
     * @return $this
     */
    public function getEmails(){
        return $this->hasMany(Emails::className(), ['employee_id' => 'id'])->where(['emails.status' => 1]);
    }

    /**
     * Возвращает текстовое представление статуса
     * @return mixed
     */
    public function getStatusStr(){
        $arr = [
            self::DISMISSED => 'Уволен',
            self::ACTIVE => 'Работает'
        ];
        return $arr[$this->status];
    }

    /**
     * Формируем массив с ФИО, ИД и Email сотрудника
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arraySnpIdMail(){
        return Employees::find()->select("snp as value, snp as label, employees.id as id, email_address as email")
            ->joinWith('emails')
            ->where("snp > ''")->andWhere('employees.status > 0')
            ->orderBy('snp')->asArray()->all();
    }
}
