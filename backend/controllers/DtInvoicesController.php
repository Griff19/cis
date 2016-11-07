<?php

namespace backend\controllers;

use app\models\DtInvoiceDevicesSearch;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoiceDevices;
use Yii;
use backend\models\DtInvoicesPaymentSearch;
use backend\models\DtInvoices;
use backend\models\DtInvoicesSearch;
use backend\models\DtEnquiryDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DtInvoicesController implements the CRUD actions for DtInvoices model.
 */
class DtInvoicesController extends Controller
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
     * Lists all DtInvoices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtInvoicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Открыть документ.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dt_id_search = new DtInvoiceDevicesSearch();
        $dt_id_provider = $dt_id_search->search(Yii::$app->request->queryParams, $id);

        $dt_ip_search = new DtInvoicesPaymentSearch();
        $dt_ip_provider = $dt_ip_search->search(Yii::$app->request->queryParams, $id);

        $dt_ed_search = new DtEnquiryDevicesSearch();
        $dt_ed_provider = $dt_ed_search->searchDevices(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dt_id_search' => $dt_id_search,
            'dt_id_provider' => $dt_id_provider,
            'dt_ip_search' => $dt_ip_search,
            'dt_ip_provider' => $dt_ip_provider,
            'dt_ed_search' => $dt_ed_search,
            'dt_ed_provider' => $dt_ed_provider
        ]);
    }

    /**
     * Creates a new DtInvoices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DtInvoices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DtInvoices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаление документа "Счет". За одно нужно удалить строки из связанной таблицы
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            $did_models = DtInvoiceDevices::findAll(['dt_invoices_id' => $model->id]);
            foreach ($did_models as $did_model){
                /** @var $did_model DtInvoiceDevices */
                DtEnquiryDevices::updateAll(['status' => DtEnquiryDevices::REQUEST_INVOICE],['id' => $did_model->dt_enquiry_devices_id]);
            }
            DtInvoiceDevices::deleteAll(['dt_invoices_id' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the DtInvoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtInvoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtInvoices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
