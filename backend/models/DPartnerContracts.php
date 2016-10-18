<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "d_partner_contracts".
 *
 * @property integer $id
 * @property string $contract_number
 * @property string $contract_date
 * @property integer $partner_id
 * @property string $title
 *
 * @property DContractSub[] $dContractSubs
 * @property DPartners $partner
 */
class DPartnerContracts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_partner_contracts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_date'], 'safe'],
            [['partner_id'], 'integer'],
            [['contract_number', 'title'], 'string', 'max' => 255],
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
            'contract_number' => 'Номер документа',
            'contract_date' => 'Дата документа',
            'title' => 'Комментарий',
            'partner_id' => 'Partner ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDContractSubs()
    {
        return $this->hasMany(DContractSub::className(), ['partner_contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(DPartners::className(), ['id' => 'partner_id']);
    }
}
