<?php

namespace backend\models;

/**
 * Модель для таблицы "coordinate".
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
 * @property string $snp - для фильтрации по владельцам рабочих мест
 * @property integer $branch_id - идентификатор филиала
 * @property array $mapParams - массив настроек карт для каждого филиала, используется для составления списка филиалов, у которых уже есть карта
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
            [['workplace_id', 'floor', 'branch_id'], 'integer'],
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
	        'branch_id' => 'Филиал:',
            'x' => 'X',
            'y' => 'Y',
            'balloon' => 'Подсказка',
            'preset' => 'Вид',
            'comment' => 'Комментарий',
	        'content' => 'Контент',
	        'snp' => 'Владелец:'
        ];
    }

	/**
	 * Готовим массив с параметрами карты для разных филиалов
     * Ключ массива это номер подразделения, значение - параметры карты
	 * @return array
	 */
    public static function arrMapParams(){
    	return [
		    1 => [ //Буланиха
			    'max_zoom' => 6,
			    'pic_width' => 9560,
			    'pic_height' => 7214
		    ],
			2 => [ //Бийск
				'max_zoom' => 6,
				'pic_width' => 2000,
				'pic_height' => 1600
			],
            3 => [ //Барнаул
                'max_zoom' => 6,
                'pic_width' => 2000,
                'pic_height' => 1153
            ],
		    8 => [ //Томск
			    'max_zoom' => 6,
			    'pic_width' => 2160,
			    'pic_height' => 1200
		    ],
            5 => [ //Новосибирск
                'max_zoom' => 6,
                'pic_width' => 2437,
                'pic_height' => 1815
            ],
	    ];
    }

	/**
	 * Определяем настройки карты для каждого филиала, возвращаем их в зависимости от номера филиала
	 * @param $id -  номер/идентификатор филиала
	 * @return mixed
	 */
    public static function getMapParams($id){
	    $mapParams = self::arrMapParams();
		if ($id > 0)
	        if (array_key_exists($id, $mapParams))
	            return $mapParams[$id];
	        else
	            return $mapParams[1];
	    else
	    	return array_keys($mapParams);
    }

    public function getWorkplace()
    {
    	return $this->hasOne(Workplaces::class, ['id' => 'workplace_id']);
    }

	/**
	 * Функция для сбора данных о владельцах рабочих мест
	 * эти данные используются для формирования фильтра на странице глобальной карты (site/map)
	 * @param int $floor
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public static function getOwners($floor = 1, $branch = 1)
    {
    	return Coordinate::find()->select('snp')
		    ->leftJoin('wp_owners', 'wp_owners.workplace_id = coordinate.workplace_id')
		    ->rightJoin('employees', 'employees.id = wp_owners.employee_id')
		    ->where(['floor' => $floor])->andWhere(['coordinate.branch_id' => $branch])
		    ->groupBy('snp')
		    ->all();
    }

	/**
	 * Получаем массыв филиалов у которых есть карта
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public static function getBranches(){
    	return Branches::find()->select('branches.id AS id, branch_title AS value')
		    ->where(['in', 'id', self::getMapParams(0)])
		    ->orderBy('id')
		    ->asArray()
		    ->all();
    }
}
