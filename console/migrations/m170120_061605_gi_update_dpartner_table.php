<?php

use yii\db\Migration;

class m170120_061605_gi_update_dpartner_table extends Migration
{
    public function up()
    {
		$this->addColumn('d_partners', 'legal_address', $this->string(255));
		$this->addColumn('d_partners', 'mailing_address', $this->string(255));
		$this->addColumn('d_partners', 'ogrn', $this->string(13));
		$this->addColumn('d_partners', 'kpp', $this->string(9));
		$this->addColumn('d_partners', 'bik', $this->string(9));
		$this->addColumn('d_partners', 'check_account', $this->string(25));
		$this->addColumn('d_partners', 'corr_account', $this->string(20));

		$this->addCommentOnColumn('d_partners', 'legal_address', 'Юридический адес');
		$this->addCommentOnColumn('d_partners', 'mailing_address', 'Почтовый адес');
		$this->addCommentOnColumn('d_partners', 'ogrn', 'ОГРН');
		$this->addCommentOnColumn('d_partners', 'kpp', 'КПП');
		$this->addCommentOnColumn('d_partners', 'bik', 'БИК');
		$this->addCommentOnColumn('d_partners', 'check_account', 'Расчетный счет');
		$this->addCommentOnColumn('d_partners', 'corr_account', 'Корр счет');
    }

    public function down()
    {
		$this->dropColumn('d_partners', 'legal_address');
		$this->dropColumn('d_partners', 'mailing_address');
		$this->dropColumn('d_partners', 'ogrn');
		$this->dropColumn('d_partners', 'kpp');
		$this->dropColumn('d_partners', 'bik');
		$this->dropColumn('d_partners', 'check_account');
		$this->dropColumn('d_partners', 'corr_account');
    }
}
