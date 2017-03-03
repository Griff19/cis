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

    /**
     * @param $id
     * @return ActiveDataProvider
     */
    public static function searchAll($id)
    {
        $query = Devices::find()
            ->select([
                'id' => 'd.id',
                'type_id' => 'd.type_id',
                'brand' => 'd.brand',
                'model' => 'd.model',
                'sn' => 'd.sn',
                'specification' => 'd.specification',
                'imei1' => 'd.imei1',
                'parent_device_id' => 'd.parent_device_id',
                'device_note' => 'd.device_note',
                'workplace_id' => 'd.workplace_id',
                'fake_device' => 'd.fake_device'
            ])
            ->from(['d' => 'devices'])
            ->leftJoin('inventory_acts_tb', 'd.id = inventory_acts_tb.device_id')
            ->where(['inventory_acts_tb.act_id' => $id])
            ->groupBy('d.id, d.brand, d.model,	d.sn,	d.specification,	d.imei1,'
                .'d.parent_device_id,	d.workplace_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
}
