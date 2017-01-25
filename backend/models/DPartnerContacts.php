<?php
/**
 * "Контакты контрагентов"
 * Модель для таблицы "d_partner_contacts" связана с моделью DPartners.
 */

namespace backend\models;

use Yii;

/**
 * @property integer $id
 * @property string $full_name
 * @property string $job_title
 * @property string $phone1
 * @property string $phone2
 * @property string $email
 * @property integer $partner_id
 * @property string $title
 * @property string $icq
 * @property string $add_number
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
			['icq', 'string', 'min' => 5, 'max' => 9, 'message' => 'Поле должно содержать от 5 до 9 цифр'],
			['add_number', 'string', 'max' => 9],
			[['icq', 'add_number'], 'match', 'pattern' => '/[0-9]/'],
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
			'add_number' => 'Доп. номер'
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
