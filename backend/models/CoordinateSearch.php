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
            [['id', 'workplace_id', 'floor'], 'integer'],
	        [['x', 'y'], 'number'],
            [['balloon', 'preset', 'comment'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Coordinate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'workplace_id' => $this->workplace_id,
            'floor' => $this->floor,
            'x' => $this->x,
            'y' => $this->y,
        ]);

        $query->andFilterWhere(['like', 'balloon', $this->balloon])
            ->andFilterWhere(['like', 'preset', $this->preset])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
