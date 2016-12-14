<?php

use yii\db\Migration;
use backend\models\DtInvoicesPayment;

class m161213_165810_newField_dtInvoicesPayment extends Migration
{
    public function up()
    {
        $this->addColumn('dt_invoices_payment', 'status', $this->integer() . ' DEFAULT ' . DtInvoicesPayment::PAY_WAITING);
        $this->addCommentOnColumn('dt_invoices_payment', 'status', 'статус текущего платежа');
        echo 'add field "status"...' . "\n\r";
    }

    public function down()
    {
        $this->dropColumn('dt_invoices_payment', 'status');
        echo 'field "status" deleted...' . "\n\r";
    }
}
