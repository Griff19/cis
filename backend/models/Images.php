<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property integer id
 * @property string linkfile
 * @property string owner
 * @property string img_group
 */
class Images extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['linkfile'], 'required'],
            [['linkfile', 'owner', 'title', 'img_group'], 'string', 'max' => 255],
            [['linkfile'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'linkfile' => 'Linkfile',
            'owner' => 'Owner',
            'title' => 'Описание изображения',
            'img_group' => 'Группа'
        ];
    }

    public static function getLinkfile($key){
        $img = Images::find()->where(['owner' => $key])->one();
        //var_dump($img);
        //die;
        return $img['linkfile'];
    }
}
