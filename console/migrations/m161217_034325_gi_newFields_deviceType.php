<?php

use yii\db\Migration;

class m161217_034325_gi_newFields_deviceType extends Migration
{
    public function up()
    {
        $this->addColumn('device_type', 'mac', $this->boolean() . ' DEFAULT FALSE');
        $this->addCommentOnColumn('device_type', 'mac', 'отображать поле mac-адреса на форме');

        $this->addColumn('device_type', 'imei', $this->boolean() . ' DEFAULT FALSE');
        $this->addCommentOnColumn('device_type', 'imei', 'отображать поле imei на форме');

        $this->update('device_type', ['mac' => true], 'id = 22'); //Видеокамера
        $this->update('device_type', ['mac' => true], 'id = 20'); //Видеорегистратор
        $this->update('device_type', ['mac' => true], 'id = 8'); //Комутатор
        $this->update('device_type', ['mac' => true], 'id = 17'); //Маршрутизатор
        $this->update('device_type', ['mac' => true], 'id = 18'); //Материнская плата
        $this->update('device_type', ['mac' => true], 'id = 39'); //Неттоп
        $this->update('device_type', ['mac' => true], 'id = 7'); //Ноутбук
        $this->update('device_type', ['mac' => true], 'id = 34'); //Планшет
        $this->update('device_type', ['mac' => true], 'id = 4'); //Принтер
        $this->update('device_type', ['mac' => true], 'id = 36'); //Сервер
        $this->update('device_type', ['mac' => true], 'id = 27'); //Сетевая карта
        $this->update('device_type', ['mac' => true], 'id = 16'); //Сканер штрих
        $this->update('device_type', ['mac' => true], 'id = 43'); //Телефон
        $this->update('device_type', ['mac' => true], 'id = 3'); //Телефон
        $this->update('device_type', ['mac' => true], 'id = 40'); //Тонкий клиент
        $this->update('device_type', ['mac' => true], 'id = 19'); //Точка доступа

        $this->update('device_type', ['imei' => true], 'id = 17'); //Модем
        $this->update('device_type', ['imei' => true], 'id = 34'); //Планшет
        $this->update('device_type', ['imei' => true], 'id = 43'); //Телефон
        
        return true;
    }

    public function down()
    {
        $this->dropColumn('device_type', 'mac');
        $this->dropColumn('device_type', 'imei');

        return true;
    }

}
