<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * EmployeesSearch represents the model behind the search form about `backend\models\Employees`.
 */
class StartEmployeesSearch extends StartEmployees
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_title', 'snp', 'job_title', 'room_title', 'workplaces_title', 'email_address'], 'string', 'max' => 255],
            //[['email_address'], 'string', 'max' => 255],
            [['cell_number'], 'string', 'max' => 12],
            [['voip_number'], 'integer']
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
        $query = StartEmployees::find()//(new Query())
            ->select("branch_title, employees.snp AS snp, employees.id AS emp_id, cell_number, voip_number, email_address, room_title, workplaces_title, workplaces.id AS wp_id, job_title")
            //->from('employees')
            ->leftJoin('branches', 'branches.id = employees.branch_id')
            ->leftJoin('cell_numbers', "cell_numbers.employee_id = employees.id AND cell_numbers.status = 1")
            ->leftJoin('emails', 'emails.employee_id = employees.id AND emails.status = 1')
            ->leftJoin('wp_owners', 'wp_owners.employee_id = employees.id AND wp_owners.status = 1')
            ->leftJoin('workplaces', 'workplaces.id = wp_owners.workplace_id')
            ->leftJoin('rooms', 'rooms.id = workplaces.room_id')
            //->leftJoin('devices', 'devices.workplace_id = workplaces.id AND devices.type_id = 3')
            ->leftJoin('voip_numbers', 'voip_numbers.workplace_id = workplaces.id AND voip_numbers.status = 1')
            ->where("cell_number > '' OR voip_number > 0 OR email_address > '' OR workplaces_title > ''");
            //->orderBy('branches.id, snp');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'branch_title' => SORT_ASC,
                'snp' => SORT_ASC
            ],
            'attributes' => [
                'branch_title' => [
                    'asc' => ['branches.id' => SORT_ASC],
                    'desc' => ['branches.id' => SORT_DESC]
                ],
                'snp' => [
                    'asc' => ['snp' => SORT_ASC],
                    'desc' => ['snp' => SORT_DESC]
                ],
                'cell_number' => [
                    'asc' => ['cell_number' => SORT_ASC],
                    'desc' => [new Expression('cell_number DESC NULLS LAST')]
                ],
                'voip_number' => [
                    'asc' => ['voip_number' => SORT_ASC],
                    'desc' => [new Expression('voip_number DESC NULLS LAST')],
                ],
                'email_address' => [
                    'asc' => ['email_address' => SORT_ASC],
                    'desc' => [new Expression('email_address DESC NULLS LAST')]
                ],
                'room_title' => [
                    'asc' => ['room_title' => SORT_ASC],
                    'desc' => [new Expression('room_title DESC NULLS LAST')]
                ],
                'job_title' => [
                    'asc' => ['job_title' => SORT_ASC],
                    'desc' => ['job_title' => SORT_DESC]
                ],
                'workplaces_title' => [
                    'asc' => ['wp_id' => SORT_ASC],
                    'desc' => [new Expression('wp_id DESC NULLS LAST')]
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }
        $query->filterWhere([
            'voip_number' => $this->voip_number,
            'branch_title' => $this->branch_title,
            'workplaces.id' => $this->workplaces_title
        ]);

        $query->andFilterWhere(['like', 'LOWER(snp)', mb_strtolower($this->snp)])
            ->andFilterWhere(['like', 'LOWER(job_title)', mb_strtolower($this->job_title)])
            ->andFilterWhere(['like', 'cell_number', $this->cell_number])
            ->andFilterWhere(['like', 'LOWER(room_title)', mb_strtolower($this->room_title)])
            //->andFilterWhere(['like', 'LOWER(workplaces_title)', mb_strtolower($this->workplaces_title)])
                ->andFilterWhere(['like', 'email_address', $this->email_address]);
            //$query->where("");
        return $dataProvider;
    }
}
