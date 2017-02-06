<?php
/**
 * Модель документа "Протокол Встречи"
 * Связыные модели начинаются на суффикс "Mm..."
 */

namespace backend\models;

use Yii;

/**
 * @property integer $id
 * @property string $doc_num
 * @property string $doc_date
 */
class MeetingMinutes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meeting_minutes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_date'], 'safe'],
            [['doc_num'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doc_num' => 'Док. №',
            'doc_date' => 'Дата Док.',
        ];
    }

}
