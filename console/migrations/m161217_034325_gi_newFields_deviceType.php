<?php

use yii\db\Migration;

class m161217_034325_gi_newFields_deviceType extends Migration
{
    public function up()
    {
        $this->addColumn('device_type', 'mac', $this->boolean() . ' DEFAULT FALSE');
        $this->addCommentOnColumn('device_type', 'mac', 'отображать mac-адрес в форме');

        $this->addColumn('device_type', 'imei', $this->boolean() . ' DEFAULT FALSE');
        $this->addCommentOnColumn('device_type', 'imei', 'отображать imei в форме');

        $this->update('device_type', ['mac' => true], 'id = 22'); //
        $this->update('device_type', ['mac' => true], 'id = 20'); //
        $this->update('device_type', ['mac' => true], 'id = 8'); //
        $this->update('device_type', ['mac' => true], 'id = 17'); //
        $this->update('device_type', ['mac' => true], 'id = 18'); //
        $this->update('device_type', ['mac' => true], 'id = 39'); //
        $this->update('device_type', ['mac' => true], 'id = 7'); //
        $this->update('device_type', ['mac' => true], 'id = 34'); //
        $this->update('device_type', ['mac' => true], 'id = 4'); //
        $this->update('device_type', ['mac' => true], 'id = 36'); //
        $this->update('device_type', ['mac' => true], 'id = 27'); //
        $this->update('device_type', ['mac' => true], 'id = 16'); //
        $this->update('device_type', ['mac' => true], 'id = 43'); //
        $this->update('device_type', ['mac' => true], 'id = 3'); //
        $this->update('device_type', ['mac' => true], 'id = 40'); //
        $this->update('device_type', ['mac' => true], 'id = 19'); //

        $this->update('device_type', ['imei' => true], 'id = 17'); //
        $this->update('device_type', ['imei' => true], 'id = 34'); //
        $this->update('device_type', ['imei' => true], 'id = 43'); //
        
        return true;
    }

    public function down()
    {
        $this->dropColumn('device_type', 'mac');
        $this->dropColumn('device_type', 'imei');

        return true;
    }

}
