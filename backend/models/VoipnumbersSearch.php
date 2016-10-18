<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\VoipNumbers;

/**
 * VoipnumbersSearch represents the model behind the search form about `backend\models\VoipNumbers`.
 */
class VoipnumbersSearch extends VoipNumbers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'voip_number', 'device_id'], 'integer'],
            [['secret', 'description', 'context'], 'safe'],
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
    public function search($params, $id_dev = 0, $id_wp = 0)
    {
        if ($id_dev > 0) {
            $query = VoipNumbers::find()->where(['device_id' => $id_dev]);
        } elseif ($id_wp > 0) {
            $query = VoipNumbers::find()->where(['workplace_id' => $id_wp]);
        } else {
            $query = VoipNumbers::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['voip_number' => SORT_ASC]],
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
            'voip_number' => $this->voip_number,
            'device_id' => $this->device_id,
        ]);

        $query->andFilterWhere(['like', 'secret', $this->secret])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'context', $this->context]);

        return $dataProvider;
    }
}
