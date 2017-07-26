<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property integer $employee_id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $password
 * @property string $newpass
 * @property string $confnewpass
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property mixed employee
 */
class User extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $employee_name;
    public $password;
    public $newpass;
    public $confnewpass;

    public static function tableName()
    {
        return 'user';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email' //'created_at', 'updated_at'
                ], 'required'],
            [['status', 'employee_id'], 'integer'],
            [['employee_name', 'username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
           // [['_1c_id'], 'string', 'max' => 36],
            ['password', 'string', 'min' => 6],
            [['newpass','confnewpass'], 'match', 'pattern' => '/^.{6,}/', 'message' => 'Пароль должен содержать минимум 6 символов'],
            [['newpass','confnewpass'], 'match', 'pattern' => '/[A-Z]{1}/', 'message' => 'Пароль должен содержать хотябы одну цифру и заглавную букву'],
            [['newpass','confnewpass'], 'match', 'pattern' => '/\d+/', 'message' => 'Пароль должен содержать хотябы одну цифру и заглавную букву'],
            [['confnewpass'],'compare','compareAttribute'=>'newpass'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'employee_name' => 'Имя сотрудника',
            'employee_id' => 'ИД Сотрудника',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Токен для сброса пароля',
            'password' => 'Пароль',
            'newpass' => 'Новый пароль',
            'confnewpass' => 'Подтверждение пароля',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }
    
    public function getRole(){
        $rolesUsr = Yii::$app->authManager->getRolesByUser($this->id);
        $roleUsr = '';
        foreach ($rolesUsr as $roleUsr){} 
        return $roleUsr;
    }

    /**
     * Получаем имя пользователя по его ид
     * @return string
     * @internal param $id
     */
    public function getName(){

        $usr = User::findOne(['id' => $this->id]);
        return $usr->username;
    }

//    public function getParent($id){
//        $usr = User::findOne(['id' => $id]);
//        $cust = Customers::findOne(['customer_name' => $usr->fullname]);
//        //$usr = User::findOne(['id' => $cust->user_id]);
//        //var_dump($cust);
//        //die;
//        return $cust->typeprices_id;
//    }

    /**
     * Связь с моделью Сотрудников
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id']);
    }

    public static function arrayUsers(){
        return User::find()->select('id as value, username as label')->orderBy('username')->asArray()->all();
    }


}
