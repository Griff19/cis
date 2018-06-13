<?php

namespace backend\controllers;

use kartik\mpdf\Pdf;
use Yii;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoices;
use backend\models\DtInvoiceDevices;
use backend\models\DtInvoiceDevicesSearch;
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
                'class' => VerbFilter::class,
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
     * @param int $dt_invoices_id Идентификатор документа "Счет"
     * @param int $id идентификатор строки в документе "Заявка на оборудование"
     * @return mixed
     */
    public function actionCreate($dt_invoices_id, $id)
    {
        /* @var $deviceEnquiry DtEnquiryDevices */
        $deviceEnquiry = DtEnquiryDevices::find()->where(['id' => $id])->one();

        /** @var $model DtInvoiceDevices */
        $model = new DtInvoiceDevices();
        $model->dt_invoices_id = $dt_invoices_id;
        $model->type_id = $deviceEnquiry['type_id'];
        $model->dt_enquiries_id = $deviceEnquiry['dt_enquiries_id'];
        $model->status = $deviceEnquiry->status + 1;
        $model->dt_enquiry_devices_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $deviceEnquiry->status = $model->status;
            $deviceEnquiry->dt_inv_id = $model->dt_invoices_id;
            $deviceEnquiry->save();

            $dt_invoices = DtInvoices::findOne($dt_invoices_id);
            $dt_invoices->status = DtInvoices::DOC_WAITING_AGREE;
            $dt_invoices->save();

            return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Добавляем устройство в счет без заявки
     * @param $dt_invoices_id
     * @return string|\yii\web\Response
     */
    public function actionAdd($dt_invoices_id)
    {
        $model = new DtInvoiceDevices();
        $model->dt_invoices_id = $dt_invoices_id;
        $model->status = DtEnquiryDevices::WAITING_AGREE;
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
        else
            return $this->render('create', ['model' => $model]);
    }

    /**
     *
     * @param integer $id
     * @return mixed
     */
    public function actionSetPrice($id, $price)
    {
        $model = $this->findModel($id);
        $model->price = $price;
        $model->save();
        return true;
    }


    /**
     * @param $id
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionSetStatus($id)
    {
        $model = $this->findModel($id);
        if ($model->status == DtEnquiryDevices::DEBIT) return 0;
        /**
         * @var $modelInvoices DtInvoices документ "Счет"
         * @var $modelEnquiry DtEnquiryDevices строка устройства в документе "Заявка"
         */
        $modelInvoices = DtInvoices::findOne($model->dt_invoices_id);
        $modelEnquiry = DtEnquiryDevices::findOne($model->dt_enquiry_devices_id);

        $summPay = $modelInvoices->summPay;
        $summ = $modelInvoices->summ;

        if ($summ > $summPay) {
            $model->status = DtEnquiryDevices::NEED_BUY; //если счет еще не оплачен то статус: "Требует оплаты"
            $modelEnquiry->status = DtEnquiryDevices::NEED_BUY;
        } else {
            $model->status = DtEnquiryDevices::PAID; //если счет закрыт то статус: "Оплачен"
            $modelEnquiry->status = DtEnquiryDevices::PAID;
        }
        $model->save();
        $modelEnquiry->save();
        return true;
    }

    /**
     * Удаляем строку устройства из табличной части документа "Счет"
     * При успешном удалении меняем статус в строке документа "Заявка"
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $dt_invoices_id = $model->dt_invoices_id;
        if ($model->delete()) {
            /** @var DtEnquiryDevices $modelEnquiry */
            $modelEnquiry = DtEnquiryDevices::findOne($model->dt_enquiry_devices_id);
            if ($modelEnquiry) {
                $modelEnquiry->status = DtEnquiryDevices::REQUEST_INVOICE;
                $modelEnquiry->save();
            }
        }
        return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
    }

    /**
     * Генерация печатного документа "Ведомость на согласование"
     * @return mixed
     */
    public function actionPdf()
    {
        $searchModel = new DtInvoiceDevicesSearch();
        $dataProvider = $searchModel->searchToEmployee(Yii::$app->request->queryParams, 'pdf');

        $type = 'на согласование';
        $this->layout = 'pdf';
        /** @var Pdf $pdf */
        $pdf = Yii::$app->pdf;
        $pdf->options = ['title' => 'Ведомость'];

        $pdf->content = $this->render('pdf', [
            'dataProvider' => $dataProvider,
            'type' => $type
        ]);
        return $pdf->render();
    }

    /**
     * @param integer $id
     * @return DtInvoiceDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtInvoiceDevices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена');
        }
    }
}
