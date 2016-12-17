<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * Модель для таблицы "device_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $synonyms
 * @property boolean $comp
 * @property boolean $mac
 * @property boolean $imei
 */
class DeviceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','synonyms'], 'string', 'max' => 255],
            [['comp', 'mac', 'imei'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тип устройства',
            'synonyms' => 'Синоним',
            'comp' => 'Комплектующее',
            'mac' => 'Отображать MAC',
            'imei' => 'Отображать IMEI'
        ];
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getTitle($id){
        $devtype = DeviceType::findOne($id);
        return $devtype['title'];
    }

    /**
     * Получаем массив типов устройств, отсортированных по частоте использования
     * Обязательно в запросе должны быть поля 'type_id' и 'title' т.к. они используются в форме
     * Задача №62 {{@link http://redmine.corp.altburenka.ru/issues/62}} изменила сортировку, этот блок пока не нужен...
     * @return array|\yii\db\ActiveRecord[]
     *
        public static function arrDevType(){
            return (new Query())->select(['type_id' => 'dt.id', 'title' => 'dt.title', 'c' => 'c'])
                ->from(['dt' => 'device_type'])
                ->leftJoin('(SELECT type_id, COUNT(*) c FROM devices GROUP BY type_id) d', 'd.type_id = dt.id')
                ->orderBy(new Expression('c DESC NULLS LAST'))
                ->all();
        }
     *
     */

    /**
     * Получаем типы устройств отсортированные по полю "usr_sort" и далее по алфавиту
     * @return array
     */
    public static function arrDevType(){
        return (new Query())->select(['type_id' => 'id', 'title' => 'title'])
            ->from('device_type')
            ->orderBy('usr_sort, title')
            ->all();
    }
}
