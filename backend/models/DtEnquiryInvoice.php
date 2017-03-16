<?php
/**
 * Промежуточная модель для связи документов "Заявка на оборудование" и "Счет"
 * @see \backend\models\DtEnquiries, \backend\models\DtInvoices
 */
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * @property int enquiry_id Идентификатор документа "Заявка на оборудование"
 * @property int invoice_id Идентификатор документа "Счет"
 */

class DtEnquiryInvoice extends ActiveRecord
{
    public static function tableName()
    {
        return 'dt_enquiry_invoice';
    }

    public function rules()
    {
        return [
            [['enquiry_id', 'invoice_id'], 'required'],
            [['enquiry_id', 'invoice_id'], 'integer'],
        ];
    }


}