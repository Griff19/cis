<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InventoryActs;
use yii\helpers\ArrayHelper;

/**
 * InventoryActsSearch represents the model behind the search form about `backend\models\InventoryActs`.
 */
class InventoryActsSearch extends InventoryActs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workplace_id', 'owner_employee_id', 'exec_employee_id', 'status'], 'integer'],
            [['act_date', 'curr_date', 'comm'], 'safe'],
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
        $id_wp = ArrayHelper::getValue($params, 'id_wp');
        if ($id_wp)

                $query = InventoryActs::find()->where(['workplace_id' => $id_wp]);

        else
            $query = InventoryActs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['act_date' => SORT_DESC]]
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
            'owner_employee_id' => $this->owner_employee_id,
            'exec_employee_id' => $this->exec_employee_id,
            'act_date' => $this->act_date,
            'curr_date' => $this->curr_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comm', $this->comm]);

        return $dataProvider;
    }
}
