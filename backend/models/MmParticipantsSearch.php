<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MmParticipants;

/**
 * MmParticipantsSearch represents the model behind the search form about `backend\models\MmParticipants`.
 */
class MmParticipantsSearch extends MmParticipants
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mm_id', 'employee_id'], 'integer'],
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
	 * Выбираем данные по "Участникам встречи"
	 *
	 * @param array $params
	 *
	 * @param int $id Идентификатор документа "Протокол встречи"
	 * @return ActiveDataProvider
	 */
    public function search($params, $id = 0)
    {
        if ($id > 0)
			$query = MmParticipants::find()->where(['mm_id' => $id]);
		else
			$query = MmParticipants::find();

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
            'mm_id' => $this->mm_id,
            'employee_id' => $this->employee_id,
        ]);

        return $dataProvider;
    }
}
