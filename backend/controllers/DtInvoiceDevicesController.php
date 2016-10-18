<?php

namespace backend\controllers;

use Yii;
use backend\models\DtInvoiceDevices;
use app\models\DtInvoiceDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DtInvoiceDevicesController implements the CRUD actions for DtInvoiceDevices model.
 */
class DtInvoiceDevicesController extends Controller
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
     * Lists all DtInvoiceDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtInvoiceDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DtInvoiceDevices model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Добавляем новое устройство в табличную часть документа Счет
     * @return mixed
     */
    public function actionCreate($dt_invoices_id, $dt_enquiries_id, $type_id)
    {

        $model = new DtInvoiceDevices();
        $model->dt_invoices_id = $dt_invoices_id;
        $model->type_id = $type_id;
        $model->dt_enquiries_id = $dt_enquiries_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DtInvoiceDevices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['dt-invoices/view', 'id' => $model->dt_invoices_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаляем строку устройства из табличной части документа Счет
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $dt_invoices_id = $model->dt_invoices_id;
        $model->delete();
        return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
    }

    /**
     * Finds the DtInvoiceDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtInvoiceDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtInvoiceDevices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
