<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Netints;

/**
 * NetintsSearch represents the model behind the search form about `backend\models\Netints`.
 */
class NetintsSearch extends Netints
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'port_count', 'device_id'], 'integer'],
            [['mac', 'vendor', 'ipaddr', 'domain_name'], 'safe'],
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
    public function search($params, $id_dev = 0)
    {
        if ($id_dev == 0) {
            $query = Netints::find();
        } else {
            $query = Netints::find()->where(['device_id' => $id_dev]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('devices');
        $query->joinWith('deviceType');
        $query->andFilterWhere([
            'netints.id' => $this->id,
            'type' => $this->type,
            'port_count' => $this->port_count,
            'device_id' => $this->device_id,
        ]);

        $query->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'ipaddr', $this->ipaddr])
            ->andFilterWhere(['like', 'domain_name', $this->domain_name]);

        return $dataProvider;
    }
}
