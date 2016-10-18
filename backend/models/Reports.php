<?php
/**
 * Модель отчетов
 */

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Reports extends ActiveRecord
{
    public $title;
    public $type_id;
    public $count;

//    public function rules(){
//        return [
//            [['type_id'], 'integer']
//        ];
//    }

    public function attributeLabels(){
        return [
            'title' => 'описание',
            'type_id' => 'тип',
            'count' => 'количество',
        ];
    }

    /**
     * Получаем данные по устройствам на рабочем месте:
     * select (''||id) sort, id, type_id, device_note, workplace_id, brand, model, sn, specification, parent_device_id
     * from devices
     * where workplace_id = {$id}
     * union
     * select (''||parent_device_id||id) sort, id, type_id, device_note, workplace_id, brand, model, sn, specification, parent_device_id
     * from devices
     * where parent_device_id in (
     * select id
     * from devices
     * where workplace_id = {$id}
     * )
     * order by sort;
     * @return ActiveDataProvider
     */
    public static function getInventoryData($id){
        $query1 = (new Query())
            ->select([
                'sort' => '(\'\'||id)',
                'id' => 'id',
                'type_id' => 'type_id',
                'device_note' => 'device_note',
                'workplace_id' => 'workplace_id',
                'brand' => 'brand',
                'model' => 'model',
                'sn' => 'sn',
                'specification' => 'specification',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['workplace_id' => $id])
            ->andWhere("parent_device_id IS NULL OR parent_device_id = 0");

        $queryId = (new Query())->select('id')->from('devices')->where(['workplace_id' => $id])->all();
        $arrId = ArrayHelper::getColumn($queryId, 'id');

        $query2 = (new Query())
            ->select([
                'sort' => '(\'\'||parent_device_id||id)',
                'id' => 'id',
                'type_id' => 'type_id',
                'device_note' => 'device_note',
                'workplace_id' => 'workplace_id',
                'brand' => 'brand',
                'model' => 'model',
                'sn' => 'sn',
                'specification' => 'specification',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['IN', 'parent_device_id', $arrId]);

        $union = (new Query())
            ->select('sort, id, type_id, device_note, workplace_id, brand, model, sn, specification, parent_device_id')
            ->from(['tab' => $query1->union($query2)])
            ->orderBy(['sort' => SORT_ASC]);

        $provider = new ActiveDataProvider(['query' => $union, 'pagination' => false]);
        return $provider;
    }
}