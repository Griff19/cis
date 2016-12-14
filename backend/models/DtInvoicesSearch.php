<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DtInvoices;

/**
 * DtInvoicesSearch represents the model behind the search form about `backend\models\DtInvoices`.
 */
class DtInvoicesSearch extends DtInvoices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'd_partners_id', 'delivery_type', 'status'], 'integer'],
            [['doc_number', 'doc_date'], 'safe'],
            [['summ'], 'number'],
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
        $query = DtInvoices::find();

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
            'doc_date' => $this->doc_date,
            'd_partners_id' => $this->d_partners_id,
            'delivery_type' => $this->delivery_type,
            'summ' => $this->summ,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number]);

        return $dataProvider;
    }
}
