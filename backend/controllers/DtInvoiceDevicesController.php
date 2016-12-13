<?php

namespace backend\controllers;

use Yii;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoices;
use backend\models\DtInvoicesPaymentSearch;
use backend\models\DtEnquiryDevicesSearch;

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
    public function actionCreate($dt_invoices_id, $dt_enquiries_id, $type_id, $id)
    {
        /* @var $deviceEnquiry DtEnquiryDevices */
        $deviceEnquiry = DtEnquiryDevices::find()->where(['id' => $id])->one();
        //var_dump($deviceEnquiry);
        //die;
        /** @var $model DtInvoiceDevices */
        $model = new DtInvoiceDevices();
        $model->dt_invoices_id = $dt_invoices_id;
        $model->type_id = $deviceEnquiry['type_id'];
        $model->dt_enquiries_id = $deviceEnquiry['dt_enquiries_id'];
        $model->status = $deviceEnquiry->status + 1;

        $model->dt_enquiry_devices_id = $id;
        $deviceEnquiry->status = $model->status;
        $deviceEnquiry->dt_inv_id = $model->dt_invoices_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $deviceEnquiry->save();
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
    public function actionAdd($dt_invoices_id){
        $model = new DtInvoiceDevices();
        $model->dt_invoices_id = $dt_invoices_id;
        $model->status = DtEnquiryDevices::AWAITING_PAYMENT;
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['dt-invoices/view', 'id' => $dt_invoices_id]);
        else
            return $this->render('create', ['model' => $model]);
    }

    /**
     * Редактировать устройство в документе счет. В данном контексте тебуется сменить только статус
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

    }

    /**
     * @param $id
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionSetStatus($id){
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
     *
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
