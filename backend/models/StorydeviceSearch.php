<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StoryDevice;

/**
 * StorydeviceSearch represents the model behind the search form about `backend\models\StoryDevice`.
 */
class StorydeviceSearch extends StoryDevice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_device'], 'integer'],
            [['id_wp', 'event'], 'string'],
            [['date_up'], 'safe'],
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
    public function search($params, $id_dev = 0)
    {
        if ($id_dev == 0) {
            $query = StoryDevice::find();
        } else {
            $query = StoryDevice::find()->where(['id_device' => $id_dev]);
        }

        $query->joinWith('user');
        $query->joinWith('workplace');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_device' => $this->id_device,
            //'id_wp' => $this->id_wp,
            'date_up' => $this->date_up,
            'event' => $this->event,
        ]);
        $query->andFilterWhere(['like', 'LOWER(workplaces.workplaces_title)', mb_strtolower($this->id_wp)]);

        return $dataProvider;
    }
}
