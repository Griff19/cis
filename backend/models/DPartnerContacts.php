<?php

namespace backend\models;

use Yii;

/**
 * Модель для таблицы "d_partner_contacts".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $job_title
 * @property string $phone1
 * @property string $phone2
 * @property string $email
 * @property integer $partner_id
 * @property string $title
 *
 * @property DPartners $partner
 */
class DPartnerContacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_partner_contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id'], 'integer'],
            [['full_name', 'job_title', 'email', 'title'], 'string', 'max' => 255],
            [['phone1', 'phone2'], 'string', 'max' => 12],
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
            'full_name' => 'Полное имя',
            'job_title' => 'Должность',
            'phone1' => '1 Тел.',
            'phone2' => '2 Тел.',
            'email' => 'Email',
            'title' => 'Комментарий',
            'partner_id' => 'Partner ID',
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
