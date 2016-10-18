<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CellNumbers;

/**
 * CellnumbersSearch represents the model behind the search form about `backend\models\CellNumbers`.
 */
class CellnumbersSearch extends CellNumbers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['employee_id'], 'string', 'max' => 255],
            [['cell_number'], 'safe'],
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
    public function search($params, $emp_id = null)
    {
        if ($emp_id == null)
            $query = CellNumbers::find();
        else
            $query = CellNumbers::find()->where(['employee_id' => $emp_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('employee');
        $query->andFilterWhere([
            'cell_numbers.id' => $this->id,
            //'employee_id' => $this->employee_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'cell_number', $this->cell_number])
            ->andFilterWhere(['like', 'employees.snp', $this->employee_id]);

        return $dataProvider;
    }
}
