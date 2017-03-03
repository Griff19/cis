<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use backend\models\InventoryActsTb;
use yii\helpers\ArrayHelper;

/**
 * InventoryActsTbSearch represents the model behind the search form about `backend\models\InventoryActsTb`.
 */
class InventoryActsTbSearch extends InventoryActsTb
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'act_id', 'device_id', 'device_workplace_id'], 'integer'],
            [['status'], 'safe'],
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
    public function search($params, $status = InventoryActs::REPLACE_DEV)
    {
        $id = ArrayHelper::getValue($params, 'id');

        if ($id > 0)
            $query = InventoryActsTb::find()->where(['act_id' => $id])->andWhere(['status' => $status]);
        else
            $query = InventoryActsTb::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'act_id' => $this->act_id,
            'device_id' => $this->device_id,
            'device_workplace_id' => $this->device_workplace_id,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
