<?php

namespace backend\models;

use Yii;

/**
 * Список рабочих мест в документе "Заявка на оборудование"
 * соответствующая таблица "dt_enquiry_workplaces".
 *
 * @property integer $dt_enquiries_id
 * @property integer $workplace_id
 * @property Workplaces workplace
 */
class DtEnquiryWorkplaces extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dt_enquiry_workplaces';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_enquiries_id', 'workplace_id'], 'required'],
            [['dt_enquiries_id', 'workplace_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dt_enquiries_id' => 'Ид док',
            'workplace_id' => 'РМ №',
        ];
    }

    /**
     * @return $this
     */
    public function getOwner(){
        return $this->hasOne(Employees::className(), ['id' => 'employee_id'])
            ->viaTable('wp_owners', ['workplace_id' => 'workplace_id']);
    }

    /**
     * Связываем с моделью Workplaces (Рабочие места)
     * @return \yii\db\ActiveQuery
     */
    public function getWorkplace(){
        return $this->hasOne(Workplaces::className(), ['id' => 'workplace_id']);
    }

    /**
     * Получаем массив идентификаторов рабочих мест
     * @param $id_enq
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function arrWpIds($id_enq) {
        return DtEnquiryWorkplaces::find()->where(['dt_enquiries_id' => $id_enq])->asArray()->all();
    }
}
