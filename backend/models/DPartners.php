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
 * @property string $legal_address
 * @property string $mailing_address
 * @property string $ogrn
 * @property string $kpp
 * @property string $bik
 * @property string $check_account
 * @property string $corr_account
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
            [['name_partner', 'brand', 'legal_address', 'mailing_address'], 'string', 'max' => 255],
            [['kpp', 'bik'], 'string', 'max' => 9],
			['type_partner', 'string', 'max' => 10],
			['inn', 'string', 'max' => 12],
			['ogrn', 'string', 'min' => 13, 'max' => 13],
			['corr_account', 'string', 'min' => 20, 'max' => 20],
			['check_account', 'string', 'min' => 20, 'max' => 25],
			[['check_account', 'corr_account', 'kpp', 'bik', 'inn', 'ogrn'], 'match', 'pattern' => '/[0-9]/', 'message' => 'Поле "{attribute}" должно содержать только цифры'],
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
			'legal_address' => 'Юр. адрес',
			'mailing_address' => 'Почтовый адрес',
			'ogrn' => 'ОГРН',
			'kpp' => 'КПП',
			'bik' => 'БИК',
			'check_account' => 'Расченый счет',
			'corr_account' => 'Корр. счет'
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
