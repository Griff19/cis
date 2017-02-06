<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mm_offer".
 *
 * @property integer $id
 * @property integer $mm_id
 * @property string $content
 */
class MmOffer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mm_offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mm_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mm_id' => 'Mm ID',
            'content' => 'Содержание предложений',
        ];
    }
}
