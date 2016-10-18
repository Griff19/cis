<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "parameters".
 *
 * @property integer $id
 * @property string $brend
 * @property string $model
 * @property string $sn
 * @property string $mac
 * @property string $ip
 * @property integer $nport
 * @property string $login
 * @property string $password
 * @property string $biospass
 * @property string $radmin
 * @property string $dns
 * @property string $voip
 * @property string $aster_pwd
 * @property string $aster_cont
 * @property string $imei
 * @property integer $id_device
 */
class Parameters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nport', 'id_device'], 'integer'],
            [['id_device'], 'required'],
            [['brend', 'model', 'sn', 'mac', 'login', 'password', 'biospass', 'radmin', 'dns', 'voip', 'aster_pwd', 'aster_cont', 'imei'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brend' => 'Brend',
            'model' => 'Model',
            'sn' => 'Sn',
            'mac' => 'Mac',
            'ip' => 'Ip',
            'nport' => 'Nport',
            'login' => 'Login',
            'password' => 'Password',
            'biospass' => 'Biospass',
            'radmin' => 'Radmin',
            'dns' => 'Dns',
            'voip' => 'Voip',
            'aster_pwd' => 'Aster Pwd',
            'aster_cont' => 'Aster Cont',
            'imei' => 'Imei',
            'id_device' => 'Id Device',
        ];
    }
}
