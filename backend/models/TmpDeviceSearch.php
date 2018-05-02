<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TmpDevice;

/**
 * TmpDeviceSearch represents the model behind the search form about `backend\models\TmpDevice`.
 */
class TmpDeviceSearch extends TmpDevice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmp_workplace_id', 'devices_id'], 'integer'],
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
        $query = TmpDevice::find()->where(['tmp_workplace_id' => $params['id']]);

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
            'tmp_workplace_id' => $this->tmp_workplace_id,
            'devices_id' => $this->devices_id,
        ]);

        return $dataProvider;
    }
}
