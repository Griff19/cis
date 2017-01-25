<?php

use yii\db\Migration;

class m170123_151639_gi_update_dpartner_contacts_table extends Migration
{
    public function up()
    {
		$this->addColumn('d_partner_contacts', 'icq', $this->string(9));
		$this->addColumn('d_partner_contacts', 'add_number', $this->string(9));

		$this->addCommentOnColumn('d_partner_contacts', 'icq', 'номер icq');
		$this->addCommentOnColumn('d_partner_contacts', 'add_number', 'добавочный тел номер');
    }

    public function down()
    {
        $this->dropColumn('d_partner_contacts', 'icq');
		$this->dropColumn('d_partner_contacts', 'add_number');
    }
}
