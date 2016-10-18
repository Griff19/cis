<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "d_partners".
 *
 * @property integer $id
 * @property string $name_partner
 * @property string $type_partner
 * @property string $brand
 * @property string $inn
 *
 * @property DDocsAcc[] $dDocsAccs
 * @property DPartnerContacts[] $dPartnerContacts
 * @property DPartnerContracts[] $dPartnerContracts
 */
class DPartners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_partner', 'brand'], 'string', 'max' => 255],
            [['type_partner'], 'string', 'max' => 10],
            [['inn'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_partner' => 'Полное наименование',
            'type_partner' => 'Форма собственности',
            'brand' => 'Наименование',
            'inn' => 'ИНН',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDDocsAccs()
    {
        return $this->hasMany(DDocsAcc::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDPartnerContacts()
    {
        return $this->hasMany(DPartnerContacts::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDPartnerContracts()
    {
        return $this->hasMany(DPartnerContracts::className(), ['partner_id' => 'id']);
    }

    public static function arrayPartners(){
        return DPartners::find()->select('name_partner as value, name_partner as label, id as id')
            ->orderBy('name_partner')->asArray()->all();
    }
}
