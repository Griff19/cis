<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "d_docs_acc".
 *
 * @property integer $id
 * @property integer $type_doc
 * @property integer $partner_id
 * @property string $doc_number
 * @property string $created_date
 *
 * @property DPartners $partner
 */
class DDocsAcc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_docs_acc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_doc', 'partner_id'], 'integer'],
            [['doc_number'], 'string'],
            [['created_date'], 'safe'],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => DPartners::className(), 'targetAttribute' => ['partner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_doc' => 'Type Doc',
            'partner_id' => 'Partner ID',
            'doc_number' => 'Doc Number',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(DPartners::className(), ['id' => 'partner_id']);
    }
}
