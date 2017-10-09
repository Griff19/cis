<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "subdivision".
 *
 * @property integer $id
 * @property string $name
 * @property integer $branch_id
 * @property integer $employee_id
 * @property integer $subdivision_id
 * @property string $description
 */
class Subdivision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subdivision';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['branch_id', 'employee_id', 'subdivision_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'branch_id' => 'Branch ID',
            'employee_id' => 'Employee ID',
            'subdivision_id' => 'Subdivision ID',
            'description' => 'Description',
        ];
    }
}
