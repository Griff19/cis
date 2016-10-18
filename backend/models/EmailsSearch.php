<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Emails;

/**
 * EmailsSearch represents the model behind the search form about `backend\models\Emails`.
 */
class EmailsSearch extends Emails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'status'], 'integer'],
            [['email_address'], 'safe'],
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
    public function search($params, $emp_id = null)
    {
        if ($emp_id == null)
            $query = Emails::find();
        else
            $query = Emails::find()->where(['employee_id' => $emp_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'status' => $this->status,
        ]);

        //$query->andFilterWhere(['like', 'email_address', $this->email_address]);

        return $dataProvider;
    }
}
