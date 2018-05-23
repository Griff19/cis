<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TmpMovingSearch represents the model behind the search form about `app\models\TmpMoving`.
 */
class TmpMovingSearch extends TmpMoving
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id', 'user_id', 'status'], 'integer'],
            [['summary', 'workplace_from', 'workplace_where'], 'safe'],
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
        $query = TmpMoving::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->summary) {
            $query->joinWith('device');
            $query->leftJoin('device_type', 'device_type.id = devices.type_id');
        }

        if ($this->workplace_from || $this->workplace_where) {
            $query->leftJoin('workplaces', 'workplaces.id = workplace_from OR workplaces.id = workplace_where')
                ->leftJoin('rooms', 'rooms.id = workplaces.room_id')
                ->leftJoin('branches', 'branches.id = workplaces.branch_id')
            ;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'device_id' => $this->device_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);
        
        if (is_numeric($this->summary))
            $query->andFilterWhere(['device_id' => $this->summary]);
        else
            $query->andFilterWhere(['ilike', 'device_type.title', $this->summary])
                ->orFilterWhere(['ilike', 'devices.sn', $this->summary])
                ->orFilterWhere(['ilike', 'devices.brand', $this->summary])
                ->orFilterWhere(['ilike', 'devices.model', $this->summary])
            ;
        if (is_numeric($this->workplace_from))
            $query->andFilterWhere(['workplace_from' => $this->workplace_from]);
        else
            $query->andFilterWhere(['ilike', 'workplaces.workplaces_title', $this->workplace_from])
                ->orFilterWhere(['ilike', 'rooms.room_title', $this->workplace_from])
                ->orFilterWhere(['ilike', 'branches.branch_title', $this->workplace_from])
            ;
        if (is_numeric($this->workplace_where))
            $query->andFilterWhere(['workplace_where' => $this->workplace_where]);
        else
            $query->andFilterWhere(['ilike', 'workplaces.workplaces_title', $this->workplace_where])
                ->orFilterWhere(['ilike', 'rooms.room_title', $this->workplace_where])
                ->orFilterWhere(['ilike', 'branches.branch_title', $this->workplace_where])
            ;

        return $dataProvider;
    }
}
