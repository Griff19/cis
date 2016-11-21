<?php

use yii\db\Migration;

class m161117_184626_gi_newFieldToDeviceType extends Migration
{
    public function up()
    {
        $this->addColumn('device_type', 'usr_sort', $this->integer() . ' DEFAULT NULL');
        $this->addCommentOnColumn('device_type', 'usr_sort', 'поле для пользовательской сортировки');
        $this->update('device_type', ['usr_sort' => 1], 'id = 3'); //Телефон
        $this->update('device_type', ['usr_sort' => 2], 'id = 2'); //Монитор
        $this->update('device_type', ['usr_sort' => 3], 'id = 1'); //Системный блок
        $this->update('device_type', ['usr_sort' => 4], 'id = 25'); //Материнская плата
        $this->update('device_type', ['usr_sort' => 5], 'id = 28'); //Процессор
        $this->update('device_type', ['usr_sort' => 6], 'id = 29'); //DDR
        $this->update('device_type', ['usr_sort' => 7], 'id = 30'); //SSD
        $this->update('device_type', ['usr_sort' => 8], 'id = 26'); //HMDD
        $this->update('device_type', ['usr_sort' => 9], 'id = 18'); //Блок питания
        $this->update('device_type', ['usr_sort' => 10], 'id = 5'); //ИБП
        $this->update('device_type', ['usr_sort' => 11], 'id = 7'); //Ноутбук
        $this->update('device_type', ['usr_sort' => 12], 'id = 4'); //Принтер
    }

    public function down()
    {
        $this->dropColumn('device_type', 'usr_sort');
    }
}
