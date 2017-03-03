<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "inventory_acts_tb".
 *
 * @property integer id
 * @property integer act_id
 * @property integer device_id
 * @property integer device_workplace_id
 * @property string status
 * @property integer aux
 *
 */
class InventoryActsTb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inventory_acts_tb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['act_id', 'device_id'], 'required'],
            [['act_id', 'device_id', 'device_workplace_id', 'aux'], 'integer'],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'act_id' => 'Act ID',
            'device_id' => 'Device ID',
            'device_workplace_id' => 'Device Workplace ID',
            'status' => 'Status',
            'aux' => 'Дополнительно'
        ];
    }

    public static function CreateTb($act_id, $dev_id, $id_wp = null, $aux = null, $status)
    {
        /* @var $model InventoryActsTb */
        $query = InventoryActsTb::find()->where(['act_id' => $act_id, 'device_id' => $dev_id]);
        $count = $query->count();
        $model = $query->one();
        if ($count > 0 && $model->status != InventoryActs::ADDITION_DEV) {
            InventoryActsTb::deleteAll(['act_id' => $act_id, 'device_id' => $dev_id]);
            return true;
        }

        $model = $query->one();
        if (!$model) {
            $model = new InventoryActsTb();
            $model->act_id = $act_id;
            $model->device_id = $dev_id;
            $model->device_workplace_id = $id_wp;
            $model->aux = $aux;
            $model->status = $status;

            if ($model->save()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getDevices()
    {
        return $this->hasOne(Devices::className(), ['id' => 'device_id']);
    }
}
