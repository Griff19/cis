<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use backend\models\AdminEmployees;

/**
 * AdminEmployeesSearch represents the model behind the search form about `backend\models\AdminEmployees`.
 */
class AdminEmployeesSearch extends AdminEmployees
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['fio', 'email'], 'string', 'max' => 255],
            ['cell_number', 'string', 'max' => 12]
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
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AdminEmployees::find()
            ->select([
                'id' => 'employees.id',
                'fio' => 'employees.snp',
                'cell_number' => 'cell_numbers.cell_number',
                'email' => 'emails.email_address',
                'job_title' => 'employees.job_title'
            ])
            ->from('employees')
            ->where('employees.status > 0')
            //->leftJoin('cell_numbers', 'cell_numbers.employee_id = employees.id')
            ->leftJoin('cell_numbers', 'cell_numbers.employee_id = employees.id AND cell_numbers.status = 1')
            ->leftJoin('emails', 'emails.employee_id = employees.id AND emails.status = 1')
            ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'LOWER(snp)', mb_strtolower($this->fio)])
            ->andFilterWhere(['like', 'emails.email_address', $this->email])
            ->andFilterWhere(['like', 'cell_numbers.cell_number', $this->cell_number]);

        return $dataProvider;
    }
}
