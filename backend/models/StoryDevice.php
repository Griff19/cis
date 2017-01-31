<?php

namespace backend\models;

use common\models\MyHelp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class StoryDevice
 * @property int id_wp
 * @property int id_device
 * @property string event
 * @property int user_id
 * @property string note
 * @package backend\models
 */
class StoryDevice extends ActiveRecord
{
    const EVENT_CREATE = 'Создание';
    const EVENT_IN = 'Приход';
    const EVENT_OUT = 'Расход';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_device', 'id_wp', 'user_id'], 'integer'],
            [['event', 'note'], 'string', 'max' => 255],
            [['date_up'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_device' => 'Id Device',
            'id_wp' => 'Рабочее место',
            'user_id' => 'Сотрудник',
            'date_up' => 'Дата изменения',
            'event' => 'Движение',
            'note' => 'Заметка'
        ];
    }

    /**
     * Добавляем запись в таблицу истории
     * @param $id_wp
     * @param $id_dev
     * @param $event
     * @param $note
     */
    public static function addStory($id_wp, $id_dev, $event, $note = null){
        $story = new StoryDevice();
        $story->id_wp = $id_wp;
        $story->id_device = $id_dev;
        $story->event = $event;
        $story->user_id = Yii::$app->user->id;
        $story->note = $note;
        if ($story->save()) {}
        else {
            Yii::$app->session->setFlash('error', serialize($story->errors));
        }
    }

    /**
     * Удаляем историю по идентификатору устройства
     * @param $id_dev
     */
    public static function delStory($id_dev){
        StoryDevice::deleteAll(['id_device' => $id_dev]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'id_wp']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Devices::className(), ['id' => 'id_device']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Получаем историю по рабочему месту
     * @param $id_wp
     * @return array
     */
    public static function getStoryWp($id_wp, $id_dev, $date){
        $query = StoryDevice::find()->where(['id_wp' => $id_wp, 'id_device' => $id_dev])
            ->andWhere("date_up >= '". $date ."'")->andWhere(['event' => StoryDevice::EVENT_OUT]);
        $provider = new ActiveDataProvider(['query' => $query]);
        return $provider->models;

    }
}
