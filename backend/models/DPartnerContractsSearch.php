<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DPartnerContracts;

/**
 * DPartnerContractsSearch represents the model behind the search form about `backend\models\DPartnerContracts`.
 */
class DPartnerContractsSearch extends DPartnerContracts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id'], 'integer'],
            [['contract_number', 'contract_date', 'title'], 'safe'],
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
    public function search($params, $partner_id)
    {
        $query = DPartnerContracts::find()->where(['partner_id' => $partner_id]);

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
            'contract_date' => $this->contract_date,
            'partner_id' => $this->partner_id,
        ]);

        $query->andFilterWhere(['like', 'contract_number', $this->contract_number])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
