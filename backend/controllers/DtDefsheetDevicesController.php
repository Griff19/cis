<?php

namespace backend\controllers;

use backend\models\Devices;
use Yii;
use backend\models\DtDefsheetDevices;
use backend\models\DtDefsheetDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DtDefsheetDevicesController implements the CRUD actions for DtDefsheetDevices model.
 * Описывает действия с "Табичной частью" документа Акт Списания
 */
class DtDefsheetDevicesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DtDefsheetDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtDefsheetDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DtDefsheetDevices model.
     * @param integer $dt_defsheets_id
     * @param integer $devices_id
     * @return mixed
     */
    public function actionView($dt_defsheets_id, $devices_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($dt_defsheets_id, $devices_id),
        ]);
    }

    /**
     * Добавление строки в табличную часть документа Акт списания.
     *
     * @param integer $id идентификатор устройсва
     * @param string $param параметры УРЛ
     * @return mixed
     */
    public function actionCreate($id, $param)
    {
        parse_str($param, $arr);
        $model = new DtDefsheetDevices();
        $device = Devices::findOne($id);

//        if ($device->workplace_id != 1) {
//            Yii::$app->session->setFlash('error', 'Устройство должно находится на Складе ОИ');
//            return $this->redirect(['dt-defsheets/view', 'id' => $arr['target_id']]);
//        }

        //var_dump($arr);
        $model->dt_defsheets_id = $arr['target_id'];
        $model->devices_id = $id;
        $model->workplace_id = $device->workplace_id;
        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if ($model->save()){
            return $this->redirect(['dt-defsheets/view', 'id' => $arr['target_id']]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Редактировать строку табличной части Акта Списания
     * @param integer $dt_defsheets_id
     * @param integer $devices_id
     * @return mixed
     */
    public function actionUpdate($dt_defsheets_id, $devices_id)
    {
        $model = $this->findModel($dt_defsheets_id, $devices_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['dt-defsheets/view', 'id' => $model->dt_defsheets_id]);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удалить строку табличной части Акта Списания.
     * @param integer $dt_defsheets_id
     * @param integer $devices_id
     * @return mixed
     */
    public function actionDelete($dt_defsheets_id, $devices_id)
    {
        $this->findModel($dt_defsheets_id, $devices_id)->delete();

        return $this->redirect(['dt-defsheets/view', 'id' => $dt_defsheets_id]);
    }


    /**
     * Finds the DtDefsheetDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $dt_defsheets_id
     * @param integer $devices_id
     * @return DtDefsheetDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dt_defsheets_id, $devices_id)
    {
        if (($model = DtDefsheetDevices::findOne(['dt_defsheets_id' => $dt_defsheets_id, 'devices_id' => $devices_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
