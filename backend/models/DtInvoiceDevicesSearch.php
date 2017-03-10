<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use backend\models\DtInvoiceDevices;

/**
 * DtInvoiceDevicesSearch represents the model behind the search form about `backend\models\DtInvoiceDevices`.
 */
class DtInvoiceDevicesSearch extends DtInvoiceDevices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dt_invoices_id', 'status', 'type_id', 'dt_enquiries_id'], 'integer'],
            [['price'], 'number'],
            [['note'], 'safe'],
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
     * Выбираем устройства, привязанные к документу "Счет" по id
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        if ($id) $query = DtInvoiceDevices::find()->where(['dt_invoices_id' => $id]);
        else $query = DtInvoiceDevices::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['dt_invoices_id' => SORT_ASC]]
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
            'type_id' => $this->type_id,
            'price' => $this->price,
            'status' => $this->status,
            'dt_enquiries_id' => $this->dt_enquiries_id

        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

    /**
     * Для страницы сотрудника it-отдела выбираем только открытые заявки и устройства без заявок
     * @param $params
     * @param string $mode Если =='pdf' то выбираются устройства для печати, иначе - для отображения на странице it_index
     * @return ActiveDataProvider
     */
	public function searchToEmployee($params, $mode = '')
	{
		$enquiry = DtEnquiries::find()->select('id')->where(['status' => DtEnquiries::DTE_COMPLETE]);
		if ($mode === '')
		    $query = DtInvoiceDevices::find()->where(['NOT IN', 'dt_enquiries_id', $enquiry])
                ->orWhere(['dt_enquiries_id' => null]);
		else
            $query = DtInvoiceDevices::find()->where(['<=', 'status', DtEnquiryDevices::AWAITING_PAYMENT])
                ->andwhere(['NOT IN', 'dt_enquiries_id', $enquiry])
                ->orWhere(['dt_enquiries_id' => null]);

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => false,
			'sort' => ['defaultOrder' => ['dt_invoices_id' => SORT_ASC]]
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
			'type_id' => $this->type_id,
			'price' => $this->price,
			'status' => $this->status,
			'dt_enquiries_id' => $this->dt_enquiries_id

		]);

		$query->andFilterWhere(['like', 'note', $this->note]);

		return $dataProvider;
	}
}
