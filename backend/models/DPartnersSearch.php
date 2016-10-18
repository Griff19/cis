<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DPartners;

/**
 * DPartnersSearch represents the model behind the search form about `backend\models\DPartners`.
 */
class DPartnersSearch extends DPartners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name_partner', 'type_partner', 'brand', 'inn'], 'safe'],
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
        $query = DPartners::find();

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
        ]);

        $query->andFilterWhere(['like', 'name_partner', $this->name_partner])
            ->andFilterWhere(['like', 'type_partner', $this->type_partner])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'inn', $this->inn]);

        return $dataProvider;
    }
}
