<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "rooms".
 *
 * @property integer $id
 * @property string $room_title
 * @property integer $branch_id
 */
class Rooms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rooms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id'], 'integer'],
            [['room_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_title' => 'Наименование',
            'branch_id' => 'Подразделение',
        ];
    }

    public function getBranch(){
        return $this->hasOne(Branches::className(), ['id' => 'branch_id']);
    }
}
