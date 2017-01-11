<?php

namespace backend\controllers;

use backend\models\DtInvoices;
use backend\models\Images;
use Yii;
use backend\models\DtInvoicesPayment;
use backend\models\DtInvoicesPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Контроллер, обслуживающий таблицу платежей по документу "Счет"
 */
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
     * Lists all DtInvoicesPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtInvoicesPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

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
     * Вводим оплату по документу "Счет"
     * @param $id int идентификатор документа "Счет"
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new DtInvoicesPayment();
        $model->dt_invoices_id = $id;
        $model->status = DtInvoicesPayment::PAY_AGREED;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $invoice = DtInvoices::findOne($model->dt_invoices_id);
            $invoice->status = DtInvoices::DOC_SAVE;
            $invoice->save();
            return $this->redirect(['dt-invoices/view', 'id' => $id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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
    public function actionAgree($id){
        $model = $this->findModel($id);
        $key = md5('dt-invoices-payment' . $id);
        if (Images::getLinkfile($key)){
            $model->status = DtInvoicesPayment::PAY_AGREED;
            if (!$model->save())
                Yii::$app->session->setFlash('error', 'Не удалось сохранить платеж с новым статусом');
        } else {
            Yii::$app->session->setFlash('error', 'Необхожимо загрузить скан счета с подписью');
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['dt-invoices/view','id' => $model->dt_invoices_id]);

    }

    /**
     * Снимает согласование
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDisagree($id){
        $model = $this->findModel($id);
        $model->status = DtInvoicesPayment::PAY_WAITING;
        $model->save();
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     *
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
