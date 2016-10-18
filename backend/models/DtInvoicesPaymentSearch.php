<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DtInvoicesPayment;

/**
 * DtInvoicesPaymentSearch represents the model behind the search form about `backend\models\DtInvoicesPayment`.
 */
class DtInvoicesPaymentSearch extends DtInvoicesPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dt_invoices_id', 'employee_id'], 'integer'],
            [['agreed_date'], 'safe'],
            [['summ'], 'number'],
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
     * Готовим провайдер с данными по оплатам счетов
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $query = DtInvoicesPayment::find()->where(['dt_invoices_id' => $id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
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
            'dt_invoices_id' => $this->dt_invoices_id,
            'agreed_date' => $this->agreed_date,
            'summ' => $this->summ,
            'employee_id' => $this->employee_id,
        ]);

        return $dataProvider;
    }
}
