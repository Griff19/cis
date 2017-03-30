<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DtInvoices;
use yii\db\ActiveRecord;

/**
 * DtInvoicesSearch represents the model behind the search form about `backend\models\DtInvoices`.
 */
class DtInvoicesSearch extends DtInvoices
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'd_partners_id', 'delivery_type', 'status'], 'integer'],
            [['doc_number', 'doc_date'], 'safe'],
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
     * Формируем провайдер из данных таблицы dt-invoices
     * @param ActiveRecord $enq_invoices записи об имеющихся Счетов по текущей заявке
     * @param array $params (мелкие изменения)
     *
     * @return ActiveDataProvider
     */
    public function search($params, $status = null)
    {
        if ($status)
            $query = DtInvoices::find()->where(['status' => $status]);
        else
            $query = DtInvoices::find();

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
            'doc_date' => $this->doc_date,
            'd_partners_id' => $this->d_partners_id,
            'delivery_type' => $this->delivery_type,
            'summ' => $this->summ,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number]);

        return $dataProvider;
    }

    /**
     * Функция подбирает документы соответствующие документу "Заявка на оборудование"
     * @param $params POST-параметры
     * @param null $enq_invoices Идентификатор документов "Заявка"
     * @return ActiveDataProvider
     */
    public function searchForEnquiry($params, $enq_invoices = null)
    {
        $query = DtInvoices::find()->where(['IN', 'id', $enq_invoices]);

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
            'doc_date' => $this->doc_date,
            'd_partners_id' => $this->d_partners_id,
            'delivery_type' => $this->delivery_type,
            'summ' => $this->summ,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number]);

        return $dataProvider;
    }
}
