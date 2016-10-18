<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkplacesSearch represents the model behind the search form about `backend\models\Workplaces`.
 */
class WorkplacesSearch extends Workplaces
{
    public $inventoryDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'voip'], 'integer'],
            [['workplaces_title', 'room_id', 'branch_id', '_owner'], 'string', 'max' => 255],
            //['ip', 'ip'],
            [['mu'], 'boolean'],
            ['inventoryDate', 'string']
            //['inventoryDate', 'match', 'pattern' => '/([0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01]))|(([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4}))/'],
            //['inventoryDate', 'match', 'pattern' => '/(([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4}))/']
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
        $query = Workplaces::find();
        $query->joinWith('room');
        $query->joinWith('branch');
        $query->joinWith('owner');
        $query->joinWith('voips');
        $query->joinWith('netints');
        $query->joinWith('inventory');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'branch_id',
                'room_id',
                'workplaces_title',
                '_owner' => [
                    'asc' => ['employees.snp' => SORT_ASC],
                    'desc' => ['employees.snp' => SORT_DESC]
                ],
                'inventoryDate' => [
                    'asc' => ['inventory_acts.act_date' => SORT_ASC],
                    'desc' => ['inventory_acts.act_date' => SORT_DESC]
                ]
            ],
            'defaultOrder' => ['branch_id' => SORT_ASC, 'room_id' => SORT_ASC]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'workplaces.id' => $this->id,
            'mu' => $this->mu,
            'voip_number' => $this->voip,
        ]);

        if ($this->inventoryDate) $query->andFilterWhere(['<=','act_date', $this->inventoryDate]);
        //if ($this->inventoryDate) $query->andFilterWhere(['<=','act_date', Yii::$app->formatter->asDate($this->inventoryDate, 'yyyy-mm-dd')]);

        $query->andFilterWhere(['like', 'LOWER(workplaces_title)', mb_strtolower($this->workplaces_title)])
        ->andFilterWhere(['like', 'LOWER(rooms.room_title)', mb_strtolower($this->room_id)])
        ->andFilterWhere(['like', 'LOWER(branches.branch_title)', mb_strtolower($this->branch_id)])
        ->andFilterWhere(['like', 'LOWER(employees.snp)', mb_strtolower($this->_owner)]);

        return $dataProvider;
    }
}
