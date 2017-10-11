<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Coordinate;

/**
 * CoordinateSearch represents the model behind the search form about `app\models\Coordinate`.
 */
class CoordinateSearch extends Coordinate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workplace_id', 'floor', 'branch_id'], 'integer'],
	        [['x', 'y'], 'number'],
            [['balloon', 'preset', 'comment'], 'safe'],
	        ['snp', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params, $floor = null, $branch = null)
    {
        $query = Coordinate::find();
        $query->joinWith('workplace')
            ->leftJoin('wp_owners', 'wp_owners.workplace_id = workplaces.id')
	        ->leftJoin('employees', 'employees.id = wp_owners.employee_id');

        if ($floor) $query->where(['floor' => $floor]);
        if ($branch) $query->andWhere(['coordinate.branch_id' => $branch]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'pagination' => false,
	        'sort' => ['defaultOrder' => ['y' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'wp_owners.workplace_id' => $this->workplace_id,
            'floor' => $this->floor,
            'coordinate.branch_id' => $this->branch_id,
            'x' => $this->x,
            'y' => $this->y,
        ]);

        $query->andFilterWhere(['like', 'balloon', $this->balloon])
            ->andFilterWhere(['like', 'preset', $this->preset])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['ilike', 'employees.snp', $this->snp]);

        return $dataProvider;
    }

    /**
     * Функция не работает. Возможно не нужна...
     * @param $floor
     * @return ActiveDataProvider
     */
    public function searchOnFloor($floor)
    {
        $query = Coordinate::find()->where();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
