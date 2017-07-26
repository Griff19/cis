<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * DevicesSearch represents the model behind the search form about `backend\models\Devices`.
 */
class DevicesSearch extends Devices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_device_id'], 'integer'],
            [['type_id', 'workplace_id', 'brand', 'model', 'sn', 'dt_title'], 'string', 'max' => 255],
            [['specification'], 'string', 'max' => 512],
            [['imei1'], 'match', 'pattern' => '/[0-9]/', 'message' => 'это числовое значение'],
            [['imei1'], 'string', 'max' => 15],
            [['device_note'], 'safe'],
            [['dev_comp'], 'boolean']
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
     * @param $id_par
     * @return ActiveDataProvider
     */
    public function searchCollapseComp($id_par)
    {
        $query = Devices::find()->from(['d' => Devices::tableName()])->where(['parent_device_id' => $id_par]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['defaultPageSize' => 50]
        ]);

        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $dataProvider;
    }


    /**
     * Создаем провайдер для стандартной таблицы устройств
     * @param array $params параметры запроса
     * @param int $id_wp идентификатор работчего места по которому отбираются устройства
     * @param int $id идентификатор родительского устройства для отбора комплектующих
     * @param int $mode режим отображения
     * @return ActiveDataProvider
     */
    public function search($params, $id_wp = 0, $id = 0, $mode = 1)
    {
        if ($id > 0) {
            $query = Devices::find()->from(['d' => Devices::tableName()])->where(['parent_device_id' => $id]);
        } elseif ($id_wp > 0) {
            $query = Devices::find()->where(['workplace_id' => $id_wp]);
        } else {
            $query = Devices::find()
                ->select([
                    'id' => 'd.id',
                    'type_id' => 'd.type_id',
                    'dt_title' => 'device_type.title',
                    'dev_comp' => 'device_type.comp',
                    'brand' => 'd.brand',
                    'model' => 'd.model',
                    'sn' => 'd.sn',
                    'specification' => 'd.specification',
                    'imei1' => 'd.imei1',
                    'parent_device_id' => 'd.parent_device_id',
                    'device_note' => 'd.device_note',
                    'workplace_id' => 'd.workplace_id',
                    'wp_title' => 'workplaces.workplaces_title',
                    'snp' => 'MAX(employees.snp)',
                    'fake_device' => 'd.fake_device'
                ])
                ->from(['d' => 'devices'])
                ->leftJoin('workplaces', 'workplaces.id = workplace_id')
                ->leftJoin('device_type', 'device_type.id = type_id')
                ->leftJoin('wp_owners', 'wp_owners.workplace_id = d.workplace_id')
                ->leftJoin('employees', 'employees.id = wp_owners.employee_id')
                ->groupBy('d.id, device_type.title, device_type.comp,	d.brand,'
                    . 'd.model,	d.sn,	d.specification,	d.imei1,'
                    . 'd.parent_device_id,	d.workplace_id,	workplaces.workplaces_title');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['defaultPageSize' => 50]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'brand' => [
                    'asc' => [new Expression('brand ASC')],
                    'desc' => [new Expression('brand DESC NULLS LAST')]
                ],
                'model' => [
                    'asc' => [new Expression('model ASC')],
                    'desc' => [new Expression('model DESC NULLS LAST')]
                ],
                'sn' => [
                    'asc' => [new Expression('sn ASC')],
                    'desc' => [new Expression('sn DESC NULLS LAST')]
                ]
            ],
            'defaultOrder' => ['id' => SORT_DESC]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'd.id' => $this->id,
            'd.workplace_id' => $this->workplace_id,
            'device_type.comp' => $this->dev_comp,
            'parent_device_id' => $this->parent_device_id
        ]);

        $query->andFilterWhere(['like', 'LOWER(device_note)', mb_strtolower($this->device_note)])
            ->andFilterWhere(['like', 'LOWER(device_type.title)', mb_strtolower($this->dt_title)])
            ->andFilterWhere(['like', 'LOWER(brand)', mb_strtolower($this->brand)])
            ->andFilterWhere(['like', 'LOWER(model)', mb_strtolower($this->model)])
            ->andFilterWhere(['like', 'LOWER(sn)', mb_strtolower($this->sn)])
            ->andFilterWhere(['like', 'LOWER(specification)', mb_strtolower($this->specification)])
            ->andFilterWhere(['like', 'LOWER(imei1)', mb_strtolower($this->imei1)]);
        return $dataProvider;


    }

    /**
     * Получаем отсортированные по родителю устройства.
     * Комплектующие оказываются под родителем
     * Нужно чтобы организовывать разворачивающиеся списки/таблицы
     * @param $params
     * @param null $id_wp
     * @return ActiveDataProvider
     */
    public function searchInventoryData($params, $id_wp = null)
    {

        $query1 = (new Query())
            ->select([
                'sort' => '(\'\'||id)',
                'id' => 'id',
                'type_id' => 'type_id',
                'device_note' => 'device_note',
                'workplace_id' => 'workplace_id',
                'brand' => 'brand',
                'model' => 'model',
                'sn' => 'sn',
                'specification' => 'specification',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['workplace_id' => $id_wp])
            ->andWhere("parent_device_id IS NULL OR parent_device_id = 0");

        $queryId = (new Query())->select('id')->from('devices')->where(['workplace_id' => $id_wp])->all();
        $arrId = ArrayHelper::getColumn($queryId, 'id');

        $query2 = (new Query())
            ->select([
                'sort' => '(\'\'||parent_device_id||id)',
                'id' => 'id',
                'type_id' => 'type_id',
                'device_note' => 'device_note',
                'workplace_id' => 'workplace_id',
                'brand' => 'brand',
                'model' => 'model',
                'sn' => 'sn',
                'specification' => 'specification',
                'parent_device_id' => 'parent_device_id'
            ])
            ->from('devices')
            ->where(['IN', 'parent_device_id', $arrId]);

        $union = (new Query())
            ->select('sort, id, type_id, device_note, workplace_id, brand, model, sn, specification, parent_device_id')
            ->from(['tab' => $query1->union($query2)])
            ->orderBy(['sort' => SORT_ASC]);

        $provider = new ActiveDataProvider(['query' => $union, 'pagination' => false]);

        $this->load($params);
        if (!$this->validate())
            return $provider;

        $union->andFilterWhere(['type_id' => $this->type_id]);

        return $provider;
    }

    /**
     * Используется для генерации таблицы для выбора комплектующих
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchComp($params)
    {
        $query = Devices::find()->where(['device_type.comp' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $query->joinWith('deviceType');
        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'devices.id' => $this->id,
            'workplace_id' => $this->workplace_id,
            'device_type.comp' => $this->dev_comp
        ]);

        $query->andFilterWhere(['like', 'LOWER(device_note)', mb_strtolower($this->device_note)])
            ->andFilterWhere(['like', 'LOWER(device_type.title)', mb_strtolower($this->dt_title)])
            ->andFilterWhere(['like', 'LOWER(brand)', mb_strtolower($this->brand)])
            ->andFilterWhere(['like', 'LOWER(model)', mb_strtolower($this->model)])
            ->andFilterWhere(['like', 'LOWER(sn)', mb_strtolower($this->sn)])
            ->andFilterWhere(['like', 'LOWER(specification)', mb_strtolower($this->specification)])
            ->andFilterWhere(['like', 'LOWER(imei1)', mb_strtolower($this->imei1)]);
        return $dataProvider;
    }

    /**
     * Отбираем устройства на рабочем месте не являющиеся комплектующими
     * @param $params
     * @param $id_wp
     * @return ActiveDataProvider
     */
    public function searchDeviceOnWp($params, $id_wp)
    {
        $query = Devices::find()->where(['workplace_id' => $id_wp])
            ->andWhere("parent_device_id IS NULL OR parent_device_id = 0");
        $query->joinWith('deviceType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'type_id' => [
                    'asc' => ['device_type.title' => SORT_ASC],
                    'desc' => ['device_type.title' => SORT_DESC]
                ],
                'device_note',
                'workplace_id',
                'brand',
                'model',
                'sn',
                'specification',
                'parent_device_id'
            ],
            'defaultOrder' => ['type_id' => SORT_ASC]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'devices.id' => $this->id,
            'workplace_id' => $this->workplace_id,
        ]);

        $query->andFilterWhere(['ilike', 'device_type.title', $this->type_id])
            ->andFilterWhere(['ilike', 'device_note', $this->device_note])
            ->andFilterWhere(['ilike', 'brand', $this->brand])
            ->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'sn', $this->sn])
            ->andFilterWhere(['ilike', 'specification', $this->specification]);

        return $dataProvider;
    }

    /**
     * Ищем все устройства, закрепленные за сотрудником, отсортированные по РМ
     * todo: Протестировать, добавить фильтры
     */
    public function searchAllDeviceEmployee($params, $employee_id = null)
    {
        $query = (new Query())->select([
            'employee_id' => 'w.employee_id',
            'workplace_id' => 'w.workplace_id',
            'workplaces_title' => 'wp.workplaces_title',
            'id' => 'd.id',
            'title' => 'dt.title',
            'device_note' => 'd.device_note',
            'brand' => 'd.brand',
            'model' => 'd.model',
            'sn' => 'd.sn',
            'specification' => 'd.specification'
        ])
            ->from(['d' => 'devices'])
            ->leftJoin('wp_owners w', 'd.workplace_id = w.workplace_id')
            ->leftJoin('workplaces wp', 'wp.id = w.workplace_id')
            ->leftJoin('device_type dt', 'd.type_id = dt.id')
            ->where("d.parent_device_id IS NULL OR d.parent_device_id = 0")
            ->andWhere(['wp.room_id' => 21])
        ;

        if ($employee_id) {
            $query->andWhere(['w.employee_id' => $employee_id]);
        }


        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => false]);
        $dataProvider->setSort([
            'attributes' => [
                'workplace_id',
                'workplaces_title',
                'id',
                'title',
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            Yii::$app->session->setFlash('error', 'Валидация не прошла');
            return $dataProvider;
        }

        $query->andFilterWhere(['d.id' => $this->id]);

        $query->andFilterWhere(['ilike', 'device_note', $this->device_note])
            ->andFilterWhere(['ilike', 'brand', $this->brand])
            ->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'sn', $this->sn])
            ->andFilterWhere(['ilike', 'specification', $this->specification]);

        return $dataProvider;
    }
}
