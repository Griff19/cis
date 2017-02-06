<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MmDecision;

/**
 * MmDecisionSearch represents the model behind the search form about `backend\models\MmDecision`.
 */
class MmDecisionSearch extends MmDecision
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mm_id'], 'integer'],
            [['content', 'due_date'], 'safe'],
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
    public function search($params, $id = 0)
    {
        if ($id > 0)
			$query = MmDecision::find()->where(['mm_id' => $id]);
		else
			$query = MmDecision::find();

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
            'mm_id' => $this->mm_id,
            'due_date' => $this->due_date,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
