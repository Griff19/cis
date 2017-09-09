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
            [['mac'], 'match', 'pattern' => '/([0-9a-fA-F]{2}([:-]|$)){6}$|([0-9a-fA-F]{4}([.]|$)){3}/'],
            [['mac'], 'string', 'max' => 17],
            [['id', 'type', 'port_count', 'device_id'], 'integer'],
            [['vendor', 'ipaddr', 'domain_name'], 'safe'],
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
        //если введен mac то сортируем таблицу и приводим маску ввода к правильному формату
        //чтобы можно было искать только по первым символам
        if ($this->mac) {
            $dataProvider->setSort(['defaultOrder' => ['mac' => SORT_ASC]]);
            $this->mac = str_replace('_', '0', $this->mac);
        }

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

        $query->andFilterWhere(['>=', 'netints.mac', $this->mac])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'ipaddr', $this->ipaddr])
            ->andFilterWhere(['like', 'domain_name', $this->domain_name]);

        return $dataProvider;
    }
}
