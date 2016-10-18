<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * AdminWorkplacesSearch represents the model behind the search form about `backend\models\AdminWorkplaces`.
 */
class AdminWorkplacesSearch extends AdminWorkplaces
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['branch_title', 'string'],
            ['status', 'integer'],
            ['room_title', 'string'],
            ['workplaces_title', 'string'],
            ['voip_number', 'integer'],
            ['snp', 'string'],
            ['cellnumber', 'integer'],
            ['email', 'string'],
            ['jobtitle', 'string'],
            ['device_type', 'string'],
            ['device_id', 'integer'],
            ['device_note', 'string'],
            ['ip', 'ip'],
            ['mac', 'string'],
            ['domain_name', 'string'],
            ['tab', 'integer']
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
        $query = AdminWorkplaces::find()
            ->select([
                'branch_title' => 'branches.branch_title',
                'id' => 'workplaces.id',
                'status' => 'wp_owners.status',
                'room_title' => 'rooms.room_title',
                'workplaces_title' => 'workplaces.workplaces_title',
                'voip_number' => 'voip_numbers.voip_number',
                'snp' => 'employees.snp',
                'cellnumber' => 'cell_numbers.cell_number',
                'email' => 'emails.email_address',
                'jobtitle' => 'employees.job_title',
                //'device_type' => 'device_type.title',
                //'device_id' => 'devices.id',
                //'device_note' => 'devices.device_note',
                //'ip' => 'netints.ipaddr',
                //'mac' => 'netints.mac',
                //'domain_name' => 'netints.domain_name'
            ])
            ->from('workplaces')
            ->leftJoin('branches', 'branches.id = workplaces.branch_id')
            ->leftJoin('rooms', 'rooms.id = workplaces.room_id')
            ->leftJoin('wp_owners', 'wp_owners.workplace_id = workplaces.id')
            ->leftJoin('devices', 'devices.workplace_id = workplaces.id')
            ->leftJoin(['sub_devices' => 'devices'], 'sub_devices.parent_device_id = devices.id')
            ->leftJoin('netints', 'netints.device_id = devices.id OR netints.device_id = sub_devices.id')
            ->leftJoin('voip_numbers', 'voip_numbers.workplace_id = workplaces.id AND voip_numbers.status = 1')
            ->leftJoin('employees', 'employees.id = wp_owners.employee_id')
            ->leftJoin('cell_numbers', 'cell_numbers.employee_id = employees.id AND cell_numbers.status = 1')
            ->leftJoin('emails', 'emails.employee_id = employees.id AND emails.status = 1')
            ->where('workplaces.id <> 119')
            //->orderBy('workplaces.id')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'workplaces.id' => $this->id,
            'netints.ipaddr' => $this->ip,
            'netints.mac' => $this->mac,
            'voip_numbers.voip_number' => $this->voip_number,

        ])
        ->andFilterWhere(['like', 'netints.domain_name', $this->domain_name]);

        return $dataProvider;
    }
}
