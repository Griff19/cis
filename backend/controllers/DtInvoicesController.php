<?php

namespace backend\controllers;

use Yii;
use backend\models\DtEnquiryInvoice;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoices;
use backend\models\DtInvoicesSearch;
use backend\models\DtInvoiceDevices;
use backend\models\DtInvoiceDevicesSearch;
use backend\models\DtInvoicesPayment;
use backend\models\DtInvoicesPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

/**
 * Контроллер для модели документа "Счет"
 */
class DtInvoicesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'index', 'create', 'create-pdf', 'pdf-agree', 'set-status', 'pdf'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['delete', 'save'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
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
     * @param string $id
     * @param int|string $mode
     * @return string
     */
    public function actionPdf($id = '', $mode = 0)
    {
        Url::remember(['site/employee-it']);
        switch ($mode) {
            case 0:
                return $this->renderAjax('../pdf_layout', ['url' => '/admin/dt-invoices/create-pdf?id=' . $id]);
                //break;
            case 1:
            case 2:
                return $this->renderAjax('../pdf_layout', ['url' => '/admin/dt-invoices/pdf-agree?mode=' . ($mode-1)]);
                //break;
        }

    }

    /**
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
     * @return mixed
     */
    public function actionPdfAgree($mode = 1)
    {
        $search = new DtInvoicesSearch();
        if ($mode == 1)
            $status = DtInvoices::DOC_WAITING_AGREE;
        else
            $status = DtInvoices::DOC_AWAITING_PAYMENT;

        $dt_invoices = $search->search(Yii::$app->request->queryParams, $status);

        $this->layout = 'pdf';
        /** @var Pdf $pdf */
        $pdf = Yii::$app->pdf;
        $pdf->options = ['title' => 'Ведомость на согласование'];
        //$pdf->filename = 'InventoryAct_'. $model->id .'_'. $model->act_date .'.pdf';
        //$pdf->content = "Содержимое";
        $pdf->content = $this->render('pdf_agree', [
            'dt_invoices' => $dt_invoices,
            'mode' => $mode,
        ]);
        return $pdf->render();
    }

    /**
     * Создание счета
     * @param integer $enquiry_id Идентификатор документа "Заявка на оборудование"
     * @return mixed
     */
    public function actionCreate($enquiry_id = 0)
    {
        $model = new DtInvoices();
        if ($enquiry_id)
            $model->status = DtEnquiryDevices::NEED_BUY;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($enquiry_id) {
                $model->enquiries = $enquiry_id;
                $model->copyFromEnquiry($model->id, $enquiry_id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'enquiry_id' => $enquiry_id,
            ]);
        }
    }

    /**
     *
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
            DtEnquiryInvoice::deleteAll(['invoice_id' => $id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Функция "сохранения" документа для преключения статуса устройств и самого документа
     * позволяет сделать документ не доступным для редактирования
     * @param $id
     * @param int $mode Режим вызова 0 - из стандартного представления, 1 - со страницы "сотрудника ит"
     * @return \yii\web\Response
     */
    public function actionSave($id, $mode = 0)
    {
        /** @var DtInvoices $model модель документа "Счет" */
        $model = $this->findModel($id);

        if ($model->saveDoc())
            if ($mode == 0) Yii::$app->session->setFlash('success', 'Документ "Счет" полностью оплачен');
            else
                if ($mode == 0) Yii::$app->session->setFlash('error', 'Счет еще не оплачен');

        if ($mode == 1) {
            $controller = new SiteController('site', $this->module);
            return $controller->actionEmployeeIt();
        } else
            return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Устанавливаем статус документа Счет. Одновнеменно меняем статусы и в строках документа
     * @param int $id Идентификатор документа Счет
     * @param int $status Устанавливаемый статус
     * @return bool
     */
    public function actionSetStatus($id, $status = 0)
    {
        $model = $this->findModel($id);
        if ($status)
            $model->status = $status;
        else
            $model->status = $model->status + 1;

        if ($model->save()) {
            //определяем статус для строки на основе статуса документа
            if ($model->status == DtInvoices::DOC_SENT_FOR_PAYMENT) {
                DtInvoiceDevices::updateAll(['status' => DtEnquiryDevices::AWAITING_PAYMENT], ['dt_invoices_id' => $id]);
                DtInvoicesPayment::updateAll(['status' => DtInvoicesPayment::PAY_REFER], ['dt_invoices_id' => $id]);
            } elseif ($model->status == DtInvoices::DOC_SAVE) {
                DtInvoiceDevices::updateAll(['status' => DtEnquiryDevices::PAID], ['dt_invoices_id' => $id]);
                DtInvoicesPayment::updateAll(['status' => DtInvoicesPayment::PAY_OK], ['dt_invoices_id' => $id]);
            }
        } else
            Yii::$app->session->setFlash('error', serialize($model->getErrors()));

        $controller = new SiteController('site', $this->module);
        return $controller->actionEmployeeIt();
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
