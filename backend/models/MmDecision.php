<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mm_decision".
 *
 * @property integer $id
 * @property integer $mm_id
 * @property string $content
 * @property string $due_date
 */
class MmDecision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mm_decision';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mm_id'], 'integer'],
            [['content'], 'string'],
            [['due_date'], 'safe'],
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
            'content' => 'Содержание решений',
            'due_date' => 'Дата исполнения',
        ];
    }
}
