<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class DtEnquiryDevicesSearch extends DtEnquiryDevices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_enquiries_id', 'type_id', 'parent_device_id', 'id'], 'integer'],
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
     * Creates data provider instance with search query applied
     * @param array $params
     * @param int $id
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $id = $id ? $id : ArrayHelper::getValue($params, 'id');
        if ($id > 0)
            $query = DtEnquiryDevices::find()->where(['dt_enquiries_id' => $id]);
        else
            $query = DtEnquiryDevices::find();

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
            'dt_enquiries_id' => $this->dt_enquiries_id,
            'type_id' => $this->type_id,
            'parent_device_id' => $this->parent_device_id,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

    /**
     * Используем эту функуию для работы документа "счет"
     * Выбираем все устройства, ожидающие оплату
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchDevices($params){
        $query = DtEnquiryDevices::find()->where(['status' => DtEnquiryDevices::REQUEST_INVOICE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'dt_enquiries_id' => $this->dt_enquiries_id,
            'type_id' => $this->type_id,
            'parent_device_id' => $this->parent_device_id,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
