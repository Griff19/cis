<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * Модель связана с таблицей "dt_defsheet_devices".
 * Модель описывыет табличную часть документа Акт Списания
 * @property integer $dt_defsheets_id
 * @property integer $devices_id
 * @property string $reason
 * @property integer $status
 * @property integer $workplace_id
 * @property integer id_def
 * @property string statusString
 * @property Devices devices
 */
class DtDefsheetDevices extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0; //добавленное
    const STATUS_127 = 1; //перемещенное на склад неисправных
    const STATUS_COMPLETE = 2; //Подобрана замена
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_defsheet_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_defsheets_id', 'devices_id'], 'required'],
            [['dt_defsheets_id', 'devices_id', 'workplace_id', 'status', 'id_def'], 'integer'],
            [['reason'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dt_defsheets_id' => 'Акт списания №',
            'workplace_id' => 'РМ №',
            'devices_id' => 'ИД устройства',
            'reason' => 'Причина списания',
            'status' => 'Статус',
            'statusString' => 'Статус',
            'id_def' => 'Ид стр.'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices(){
        return $this->hasOne(Devices::className(), ['id' => 'devices_id']);
    }

    /**
     * Получаем списанные устройства с указанных рабочих мест
     * @param $arr_wp[] - массив id рабочих мест
     * @return ActiveDataProvider
     */
    public static function Devices127($arr_wp){
        $query = DtDefsheetDevices::find()->where(['workplace_id' => $arr_wp])->andWhere(['>=', 'status', 1]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $provider;
    }

    /**
     * Готовим массив строк статуса
     * @return array
     */
    public static function arrStatusString(){
        return [
            self::STATUS_NEW => 'На списание',
            self::STATUS_127 => 'Списано',
            self::STATUS_COMPLETE => 'Подобрана замена',
        ];
    }

    /**
     * Получаем статус текущей строки документа
     * @return mixed
     */
    public function getStatusString(){
        $arr = self::arrStatusString();
        return $arr[$this->status];
    }
}
