<?php
/**
 * Модель выборки данных по платежам по счету
 */
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DtInvoicesPaymentSearch extends DtInvoicesPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
			['dt_invoices_id', 'integer', 'except' => 'to_employee'],
			['dt_invoices_id', 'string', 'on' => 'to_employee'],
            [['agreed_date'], 'date'],
            [['summ'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        $scenario = Model::scenarios();
		$scenario['to_employee'] = ['id', 'dt_invoices_id', 'employee_id', 'agreed_date', 'summ'];
		return $scenario;
    }

	/**
	 * Готовим провайдер с данными по оплатам счетов
	 * @param array $params
	 * @param null $id
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

	/**
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function searchPayments($params){
		$query = DtInvoicesPayment::find();

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
		$query->joinWith('dtInvoice');
		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'agreed_date' => $this->agreed_date,
			'summ' => $this->summ,
			'employee_id' => $this->employee_id,
		]);

		$query->andFilterWhere(['ilike', 'dt_invoices.doc_number', $this->dt_invoices_id]);
		if ((int)$this->dt_invoices_id > 0)
			$query->orFilterWhere(['dt_invoices.id' => (int)$this->dt_invoices_id]);

		return $dataProvider;
	}
}
