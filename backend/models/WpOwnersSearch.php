<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WpOwners;

/**
 * WpOwnersSearch represents the model behind the search form about `backend\models\WpOwners`.
 */
class WpOwnersSearch extends WpOwners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workplace_id'], 'integer'],
            [['employee_id'], 'string', 'max' => 255],
            [['event'], 'boolean'],
            [['date'], 'safe'],
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
    public function search($params, $id_wp = 0)
    {
        if ($id_wp == 0) {
            $query = WpOwners::find();
        } else {
            $query = WpOwners::find()->where(['workplace_id' => $id_wp]);
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
        $query->joinWith('employee');
        $query->andFilterWhere([
            //'workplace_id' => $this->workplace_id,
            //'employee_id' => $this->employee_id,
            'event' => $this->event,
            'date' => $this->date,
        ]);
        $query->andFilterWhere(['like', 'employees.snp', $this->employee_id]);

        return $dataProvider;
    }
}
