<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * DtDefsheetDevicesSearch represents the model behind the search form about `backend\models\DtDefsheetDevices`.
 */
class DtDefsheetDevicesSearch extends DtDefsheetDevices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_defsheets_id', 'devices_id', 'status', 'id_def'], 'integer'],
            [['reason'], 'safe'],
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
        $id = ArrayHelper::getValue($params, 'id');
        if($id > 0)
            $query = DtDefsheetDevices::find()->Where(['dt_defsheets_id' => $id]);
        else
            $query = DtDefsheetDevices::find();

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
            'dt_defsheets_id' => $this->dt_defsheets_id,
            'devices_id' => $this->devices_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
