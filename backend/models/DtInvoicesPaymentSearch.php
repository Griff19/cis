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
	 * Готовим провайдер с данными по оплатам счетов в общем и для конкретного счета
	 * @param array $params
	 * @param null $id Идентификатор документа "Счет"
	 * @return ActiveDataProvider
	 */
    public function search($params, $id = null)
    {
        if ($id > 0)
			$query = DtInvoicesPayment::find()->where(['dt_invoices_id' => $id]);
		else
			$query = DtInvoicesPayment::find();

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
	 * Отбор данных по всем платежам
	 * Связан с документом счет
	 * @param $params
	 * @param int $status Статус платежа, позволяет отобрать конкретные данные для Ведомости
	 * @return ActiveDataProvider
	 */
	public function searchPayments($params, $status = 0){
		if ($status > 0)
			$query = DtInvoicesPayment::find()->where(['dt_invoices_payment.status' => $status]);
		else
			$query = DtInvoicesPayment::find();

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
