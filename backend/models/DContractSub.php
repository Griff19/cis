<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "d_contract_sub".
 *
 * @property string $type_contr
 * @property integer $branch_id
 * @property string $specification
 * @property string $account
 * @property boolean $payment_type
 * @property string $payment_deadline
 * @property integer $payment_freq
 * @property integer $payment_cost
 * @property boolean $edm
 * @property string $doc_receipt_date_unorig
 * @property string $doc_receipt_date_orig
 * @property boolean $uniq_bill_num
 * @property boolean $have_utd
 * @property boolean $have_act
 * @property boolean $topical
 * @property integer $partner_contract_id
 * @property integer $id
 *
 * @property DPartnerContracts $partnerContract
 */
class DContractSub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_contract_sub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'payment_freq', 'payment_cost', 'partner_contract_id'], 'integer'],
            [['payment_type', 'edm', 'uniq_bill_num', 'have_utd', 'have_act', 'topical'], 'boolean'],
            [['payment_deadline', 'doc_receipt_date_unorig', 'doc_receipt_date_orig'], 'safe'],
            [['type_contr', 'specification', 'account'], 'string', 'max' => 255],
            [['partner_contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => DPartnerContracts::className(), 'targetAttribute' => ['partner_contract_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_contr' => 'Type Contr',
            'branch_id' => 'Branch ID',
            'specification' => 'Specification',
            'account' => 'Account',
            'payment_type' => 'Payment Type',
            'payment_deadline' => 'Payment Deadline',
            'payment_freq' => 'Payment Freq',
            'payment_cost' => 'Payment Cost',
            'edm' => 'Edm',
            'doc_receipt_date_unorig' => 'Doc Receipt Date Unorig',
            'doc_receipt_date_orig' => 'Doc Receipt Date Orig',
            'uniq_bill_num' => 'Uniq Bill Num',
            'have_utd' => 'Have Utd',
            'have_act' => 'Have Act',
            'topical' => 'Topical',
            'partner_contract_id' => 'Partner Contract ID',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerContract()
    {
        return $this->hasOne(DPartnerContracts::className(), ['id' => 'partner_contract_id']);
    }
}
