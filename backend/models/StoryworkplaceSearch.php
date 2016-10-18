<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StoryWorkplace;

/**
 * StoryworkplaceSearch represents the model behind the search form about `backend\models\StoryWorkplace`.
 */
class StoryworkplaceSearch extends StoryWorkplace
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_wp', 'id_employee', 'event'], 'integer'],
            [['date_up'], 'safe'],
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
    public function search($params, $id_wp = 0)
    {
        if($id_wp == 0) {
            $query = StoryWorkplace::find();
        } else {
            $query = StoryWorkplace::find()->where(['id_wp' => $id_wp]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_wp' => $this->id_wp,
            'id_employee' => $this->id_employee,
            'date_up' => $this->date_up,
            'event' => $this->event,
        ]);

        return $dataProvider;
    }
}
