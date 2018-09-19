<?php

namespace backend\models;

use Yii;

/**
 * Class Branches
 * @package backend\models
 * @property string $branch_title
 * @property string $lannet
 * @property string $city_address
 */
class Branches extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_title', 'lannet', 'city_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_title' => 'Подразделение',
            'lannet' => 'Подсеть',
            'city_address' => 'Адрес',
        ];
    }

    /**
     * @param $name
     * @return null|static
     */
    public static function getIdByName($name){
        $branch = parent::findOne(['branch_title' => $name]);
        if ($branch) {
            return $branch->id;
        } else {
            return 0;
        }
    }

    public static function arrayBranches(){
        return Branches::find()->select('branch_title as value, branch_title as label, id as id')->orderBy('id')->asArray()->all();
    }

    /**
     * Выбираем подразделения в зависимости от роли пользователя
     * Аудитор не должен видеть Буланиху
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public static function getList()
    {
        if (Yii::$app->user->can('sysadmin'))
            return Branches::find()->all();
        if (Yii::$app->user->can('auditor'))
            return Branches::find()->where(['<>', 'id', 1])->all();
    }

}
