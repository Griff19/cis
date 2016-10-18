<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Parameters;

/**
 * ParametersSearch represents the model behind the search form about `backend\models\Parameters`.
 */
class ParametersSearch extends Parameters
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nport', 'id_device'], 'integer'],
            [['brend', 'model', 'sn', 'mac', 'ip', 'login', 'password', 'biospass', 'radmin', 'dns', 'voip', 'aster_pwd', 'aster_cont', 'imei'], 'safe'],
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
        $query = Parameters::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'nport' => $this->nport,
            'id_device' => $this->id_device,
        ]);

        $query->andFilterWhere(['like', 'brend', $this->brend])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'sn', $this->sn])
            ->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'biospass', $this->biospass])
            ->andFilterWhere(['like', 'radmin', $this->radmin])
            ->andFilterWhere(['like', 'dns', $this->dns])
            ->andFilterWhere(['like', 'voip', $this->voip])
            ->andFilterWhere(['like', 'aster_pwd', $this->aster_pwd])
            ->andFilterWhere(['like', 'aster_cont', $this->aster_cont])
            ->andFilterWhere(['like', 'imei', $this->imei]);

        return $dataProvider;
    }
}
