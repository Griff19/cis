<?php

namespace backend\controllers;

use Yii;
use backend\models\ReportsSearch;
use backend\models\Reports;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\models\Devices;
use backend\models\DeviceType;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class ReportsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['aindex', 'aemployee', 'inventory'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function actionAindex(){
        $count = Devices::find()->count();
//        $query = (new Query())
//            ->select('title, type_id, COUNT(devices.id) as count')
//            ->from('device_type, devices')
//            ->where('type_id = device_type.id')
//            ->groupBy('type_id, title')
//            ->orderBy('title');
//
//        $dp = new ActiveDataProvider([
//            'query' => $query,
//        ]);
        $searchModel = new ReportsSearch();
        $dp = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('a_index', [
            'count' => $count,
            'sm' => $searchModel,
            'dp' => $dp
        ]);
    }

    public function actionAemployee($type_id){
        $dev = DeviceType::findOne(['id' => $type_id]);
        $title = $dev->title;
        //
        $query1 = (new Query())
            ->select('workplace_id')
            ->from('devices')
            ->where(['type_id' => $type_id])
            ->groupBy('workplace_id');

        //
        $query = (new Query())
            ->select('employees.branch_id, workplace_id, workplaces_title, snp, job_title, date')
            ->from('wp_owners, employees, workplaces')
            ->where(['workplace_id' => $query1])
            ->andWhere('employees.id = wp_owners.employee_id')
            ->andWhere('workplaces.id = workplace_id');
        $dp = new ActiveDataProvider(['query' => $query]);

        return $this->render('a_employee', [
            'dp' => $dp,
            'title' => $title,
            'type_id' => $type_id
        ]);
    }

    public function actionInventory($id){
        $provider = Reports::getInventoryData($id);
        return $this->render('inventory_index',[
            'provider' => $provider,
            'id' => $id
        ]);
    }


}