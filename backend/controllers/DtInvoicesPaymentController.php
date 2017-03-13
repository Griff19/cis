<?php
/**
 * Контроллер, обслуживающий таблицу платежей по документу "Счет"
 */

namespace backend\controllers;

use backend\models\DtEnquiryDevices;
use backend\models\DtInvoiceDevices;
use backend\models\DtInvoices;
use backend\models\Images;
use backend\models\DtInvoicesPayment;
use backend\models\DtInvoicesPaymentSearch;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

class DtInvoicesPaymentController extends Controller
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
     * Не используется
     * Lists all DtInvoicesPayment models.
     * @return mixed

    public function actionIndex()
     * {
     * $searchModel = new DtInvoicesPaymentSearch();
     * $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     * return $this->render('index', [
     * 'searchModel' => $searchModel,
     * 'dataProvider' => $dataProvider,
     * ]);
     * }*/

    /**
     * Displays a single DtInvoicesPayment model.
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
     * Добавляем новый платеж по счету
     * @param integer $id Идентификатор счета
     * @param bool $is_modal Признак открытия в модальном окне
     * @param int $idid Идентификатор устройства в счете
     * @return string|\yii\web\Response
     */
    public function actionCreate($id, $is_modal = false, $idid = 0)
    {
        $model = new DtInvoicesPayment();
        $model->dt_invoices_id = $id;
        $model->status = DtInvoicesPayment::PAY_AGREED;
        /** @var DtInvoices $dt_invoices */
        $dt_invoices = DtInvoices::findOne($model->dt_invoices_id);
        $model->summ = $dt_invoices->summ;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $err = '';
                /** @var DtInvoiceDevices $model_id */
                if ($idid) {
                    $model_id = DtInvoiceDevices::findOne($idid);
                    $model_id->status = DtEnquiryDevices::AWAITING_PAYMENT;
                    if (!$model_id->save()) $err .= serialize($model_id->getErrors()) . '<br/>';

                    if ($model_id->dt_enquiry_devices_id) {
                        /** @var DtEnquiryDevices $model_ed */
                        $model_ed = DtEnquiryDevices::findOne($model_id->dt_enquiry_devices_id);
                        $model_ed->status = DtEnquiryDevices::AWAITING_PAYMENT;
                        if (!$model_ed->save()) $err .= serialize($model_ed->getErrors()) . '<br/>';
                    }
                } else {
                    DtInvoiceDevices::updateAll(['status' => DtEnquiryDevices::AWAITING_PAYMENT], ['dt_invoices_id' => $id]);
                    DtEnquiryDevices::updateAll(['status' => DtEnquiryDevices::AWAITING_PAYMENT], ['dt_inv_id' => $id]);
                }

                $dt_invoices->status = DtInvoices::DOC_SAVE;

                if (!$dt_invoices->save()) $err = serialize($dt_invoices->getErrors());
                if ($err)
                    Yii::$app->session->setFlash('error', $err);
            }

            if ($is_modal)
                return $this->redirect(['site/employee-it']);
            else
                return $this->redirect(['dt-invoices/view', 'id' => $id]);
        } else {
            if (Yii::$app->request->isAjax)
                return $this->renderAjax('create', ['model' => $model]);
            else
                return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * @param $status
     * @return mixed
     */
    public function actionPdf($status)
    {

        $searchModel = new DtInvoicesPaymentSearch();
        $dataProvider = $searchModel->searchPayments(Yii::$app->request->queryParams, $status);

        $type = 'на оплату';
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
     * Updates an existing DtInvoicesPayment model.
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
     * При удалении строки оплаты переходим обратно в документ
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
     * Устанавливает статус платежа "Согласован" проверив загружен ли скан счета с подписью
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAgree($id)
    {
        $model = $this->findModel($id);
        $key = md5('dt-invoices-payment' . $id);
        if (Images::getLinkfile($key)) {
            $model->status = DtInvoicesPayment::PAY_AGREED;
            if (!$model->save())
                Yii::$app->session->setFlash('error', 'Не удалось сохранить платеж с новым статусом');
        } else {
            Yii::$app->session->setFlash('error', 'Необхожимо загрузить скан счета с подписью');
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['dt-invoices/view', 'id' => $model->dt_invoices_id]);

    }

    /**
     * Снимает согласование
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDisagree($id)
    {
        $model = $this->findModel($id);
        $model->status = DtInvoicesPayment::PAY_WAITING;
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Устанавливаем статус
     * @param $id
     * @param $status
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSetStatus($id, $status)
    {

        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->status = $status;
        if (!$model->save())
            Yii::$app->session->setFlash('payment_error', serialize($model->getErrors()));

        $controller = new SiteController('site', $this->module);
        return $controller->actionEmployeeIt();
    }

    /**
     * @param integer $id
     * @return DtInvoicesPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtInvoicesPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
