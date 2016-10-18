<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DtEnquiryWorkplaces;
use yii\helpers\ArrayHelper;

/**
 * DtEnquiryWorkplacesSearch represents the model behind the search form about `backend\models\DtEnquiryWorkplaces`.
 */
class DtEnquiryWorkplacesSearch extends DtEnquiryWorkplaces
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_enquiries_id', 'workplace_id'], 'integer'],
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
     * @param int $id идентификатор документа заявки
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $id = $id ? $id : ArrayHelper::getValue($params, 'id');
        if ($id)
            $query = DtEnquiryWorkplaces::find()->where(['dt_enquiries_id' => $id]);
        else
            $query = DtEnquiryWorkplaces::find();

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
            'dt_enquiries_id' => $this->dt_enquiries_id,
            'workplace_id' => $this->workplace_id,
        ]);

        return $dataProvider;
    }
}
