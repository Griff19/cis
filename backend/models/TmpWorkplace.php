<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tmp_workplace".
 *
 * @property integer $id
 * @property integer $workplaces_id
 * @property Workplaces $workplace
 */
class TmpWorkplace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_workplace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplaces_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workplaces_id' => 'Связанное РМ',
        ];
    }

    /**
     * Связываем с таблицей Рабочие места
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'workplaces_id']);
    }
}
