<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employees;

/**
 * EmployeesSearch represents the model behind the search form about `backend\models\Employees`.
 */
class EmployeesSearch extends Employees
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            ['employee_number', 'string', 'max' => 10],
            ['unique_1c_number', 'string', 'max' => 36],
            [['branch_id', 'snp'], 'string', 'max' => 255],
            [['surname', 'name', 'patronymic', 'job_title'], 'safe']
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
    public function search($params)
    {
        $query = Employees::find()->where('status > 0');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['branch_id' => SORT_ASC, 'snp' => SORT_ASC]],
            'pagination' => ['pagesize' => 100]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('branch');
        $query->andFilterWhere([
            'employees.id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'patronymic', $this->patronymic])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'employee_number', $this->employee_number])
            ->andFilterWhere(['like', 'unique_1c_number', $this->unique_1c_number])
            ->andFilterWhere(['like', 'LOWER(snp)', mb_strtolower($this->snp) . '%', false])
            ->andFilterWhere(['like', 'branches.branch_title', $this->branch_id]);

        return $dataProvider;
    }
}
