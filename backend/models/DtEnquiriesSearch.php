<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DtEnquiries;

/**
 * DtEnquiriesSearch represents the model behind the search form about `backend\models\DtEnquiries`.
 */
class DtEnquiriesSearch extends DtEnquiries
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
            [['status', 'employee_name'], 'string'],
            [['create_date', 'do_date', 'create_time'], 'safe'],
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
     * Собираем данные по заявкам на оборудование
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DtEnquiries::find();

        // add conditions that should always apply here
        $query->joinWith('employee');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'create_date',
                    'do_date',
                    'employee_name' => ['asc' => ['employees.snp' => SORT_ASC], 'desc' => ['employees.snp' => SORT_DESC]],
                    'status'
                ]
            ]
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
            'employee_id' => $this->employee_id,
            'create_date' => $this->create_date,
            'do_date' => $this->do_date,
            'create_time' => $this->create_time,
            //'workplace_id' => $this->workplace_id,
            'dt_enquiries.status' => $this->status,
        ]);
        $query->andFilterWhere(['ilike', 'employees.snp', $this->employee_name]);

        return $dataProvider;
    }
}
