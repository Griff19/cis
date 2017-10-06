<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "coordinate".
 *
 * @property integer $id
 * @property integer $workplace_id
 * @property integer $floor
 * @property integer $x
 * @property integer $y
 * @property string $balloon
 * @property string $preset
 * @property string $comment
 * @property string $content
 * @property string $snp
 */
class Coordinate extends \yii\db\ActiveRecord
{
    public $snp;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coordinate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplace_id', 'floor'], 'integer'],
	        [['x', 'y'], 'number'],
            [['comment', 'snp'], 'string'],
	        ['content', 'string', 'max' => 32],
            [['balloon', 'preset'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workplace_id' => 'Рабочее место',
            'floor' => 'Этаж',
            'x' => 'X',
            'y' => 'Y',
            'balloon' => 'Подсказка',
            'preset' => 'Вид',
            'comment' => 'Комментарий',
	        'content' => 'Контент',
	        'snp' => 'Владелец:'
        ];
    }

    public function getWorkplace()
    {
    	return $this->hasOne(Workplaces::className(), ['id' => 'workplace_id']);
    }

	/**
	 * Функция для сбора данных о владельцах рабочих мест
	 * эти данные используются для формирования фильтра на странице глобальной карты (site/map)
	 * @param int $floor
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public static function getOwners($floor = 1)
    {
    	return Coordinate::find()->select('snp')
		    ->leftJoin('wp_owners', 'wp_owners.workplace_id = coordinate.workplace_id')
		    ->rightJoin('employees', 'employees.id = wp_owners.employee_id')
		    ->where(['floor' => $floor])
		    ->groupBy('snp')
		    ->all();
    }
}
