<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rooms;

/**
 * RoomsSearch represents the model behind the search form about `backend\models\Rooms`.
 */
class RoomsSearch extends Rooms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'branch_id'], 'integer'],
            [['room_title'], 'safe'],
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
        $query = Rooms::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'room_title', $this->room_title]);

        return $dataProvider;
    }
}
