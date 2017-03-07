<?php

namespace backend\controllers;

use Yii;
use backend\models\DtEnquiryDevices;
//use backend\models\DtEnquiryDevicesSearch;
use backend\models\DtInvoices;
use backend\models\DtInvoicesSearch;
use backend\models\DtInvoiceDevices;
use backend\models\DtInvoiceDevicesSearch;
use backend\models\DtInvoicesPayment;
use backend\models\DtInvoicesPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

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
     * @param int $mode
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $mode = 0)
    {
        $dt_id_search = new DtInvoiceDevicesSearch();
        $dt_id_provider = $dt_id_search->search(Yii::$app->request->queryParams, $id);

        $dt_ip_search = new DtInvoicesPaymentSearch();
        $dt_ip_provider = $dt_ip_search->search(Yii::$app->request->queryParams, $id);

//        $dt_ed_search = new DtEnquiryDevicesSearch();
//        $dt_ed_provider = $dt_ed_search->searchDevices(Yii::$app->request->queryParams);

        if ($mode == 0)
            return $this->render('view', [
                'model' => $this->findModel($id),
                'dt_id_search' => $dt_id_search,
                'dt_id_provider' => $dt_id_provider,
                'dt_ip_search' => $dt_ip_search,
                'dt_ip_provider' => $dt_ip_provider,
//                'dt_ed_search' => $dt_ed_search,
//                'dt_ed_provider' => $dt_ed_provider
            ]);
        else
            return $this->renderAjax('view_to_enquiry', [
                'model' => $this->findModel($id),
                //'dt_id_search' => $dt_id_search,
                'dt_id_provider' => $dt_id_provider,
                //'dt_ip_search' => $dt_ip_search,
                'dt_ip_provider' => $dt_ip_provider,
                //'dt_ed_search' => $dt_ed_search,
                //'dt_ed_provider' => $dt_ed_provider
            ]);
    }

    /**
     *
     * Формирование pdf-документа
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreatePdf($id)
    {
        $model = $this->findModel($id);
        //устройства в счете
        $dt_id_search = new DtInvoiceDevicesSearch();
        $dt_id_provider = $dt_id_search->search(Yii::$app->request->queryParams, $id);
        //оплаты по счету
        $dt_ip_search = new DtInvoicesPaymentSearch();
        $dt_ip_provider = $dt_ip_search->search(Yii::$app->request->queryParams, $id);

        $this->layout = 'pdf';
        /** @var Pdf $pdf */
        $pdf = Yii::$app->pdf;
        $pdf->options = ['title' => 'Счет ID' . $model->id];
        //$pdf->filename = 'InventoryAct_'. $model->id .'_'. $model->act_date .'.pdf';
        //$pdf->content = "Содержимое";
        $pdf->content = $this->render('pdf', [
            'model' => $model,
            'dt_id_provider' => $dt_id_provider,
            'dt_ip_provider' => $dt_ip_provider
        ]);
        return $pdf->render();
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
            /** @var DtInvoiceDevices[] $did_models устройства в документе "Счет" */
            $did_models = DtInvoiceDevices::findAll(['dt_invoices_id' => $model->id]);
            foreach ($did_models as $did_model) {
                DtEnquiryDevices::updateAll(
                    ['status' => DtEnquiryDevices::REQUEST_INVOICE,
                        'dt_inv_id' => null
                    ],
                    ['id' => $did_model->dt_enquiry_devices_id]);
            }
            DtInvoiceDevices::deleteAll(['dt_invoices_id' => $model->id]);
            DtInvoicesPayment::deleteAll(['dt_invoices_id' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Функция "сохранения" документа для преключения статуса устройств и самого документа
     * позволяет сделать документ не доступным для редактирования
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSave($id, $mode = 0)
    {
        /** @var DtInvoices $model модель документа "Счет" */
        $model = $this->findModel($id);

        if ($model->saveDoc())
            Yii::$app->session->setFlash('success', 'Документ "Счет" полностью оплачен');
        else
            Yii::$app->session->setFlash('error', 'Счет еще не оплачен');

        if ($mode == 1) {
            $controller = new SiteController('site', $this->module);
            return $controller->actionEmployeeIt();
        } else
            return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Устанавливаем статус для платежа
     * @param int $id Идентификаторм платежа
     * @param int $status Устанавливаемый статус
     * @return \yii\web\Response
     */
    public function actionSetStatusPayment($id, $status)
    {

        $model = DtInvoicesPayment::findOne($id);
        if ($model->setStatusDoc($status)) ;
        //Yii::$app->session->setFlash('success', 'Статус изменен');
        else
            Yii::$app->session->setFlash('error', 'Новый статус не установлен');

        return $this->actionView($model->dt_invoices_id);

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
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
