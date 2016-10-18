<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 09.02.2016
 * Time: 20:00
 */

namespace backend\models;

use yii\base\Model;
use yii\db\Query;
use yii\data\ActiveDataProvider;

class ReportsSearch extends Reports
{
    public function rules(){
        return [
            [['type_id'], 'integer'],
            [['title'], 'string']
        ];
    }

    public function scenarios(){
        return Model::scenarios();
    }

    public function search($params){
        $query = (new Query())
            ->select('title, type_id, COUNT(devices.id) as count')
            ->from('device_type, devices')
            ->where('type_id = device_type.id')
            ->groupBy('type_id, title');

        $dp = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dp->setSort([
            'attributes' => [
                'type_id',
                'title' => ['default' => SORT_ASC],
                'count'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dp;
        }

        $query->andFilterWhere([
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dp;
    }
}