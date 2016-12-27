<?php

namespace backend\controllers;

use Yii;
use backend\models\DtEnquiryDevices;
use backend\models\DtEnquiryDevicesSearch;
use backend\models\DtEnquiryWorkplacesSearch;
use backend\models\User;
use backend\models\Images;
use backend\models\Tasks;
use backend\models\DtEnquiries;
use backend\models\DtEnquiriesSearch;
use backend\models\DtInvoicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * DtEnquiriesController implements the CRUD actions for DtEnquiries model.
 */
class DtEnquiriesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create','update','delete', 'save', 'un-save', 'index-agree'],
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
     * Lists all DtEnquiries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtEnquiriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Открыть документ Заявка на оборудование.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dedSearch = new DtEnquiryDevicesSearch();
        $dedProvider = $dedSearch->search(Yii::$app->request->queryParams);

        $wpSearch = new DtEnquiryWorkplacesSearch();
        $wpProvider = $wpSearch->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dedSearch' => $dedSearch,
            'dedProvider' => $dedProvider,
            'wpProvider' => $wpProvider
        ]);
    }

    /**
     * Creates a new DtEnquiries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DtEnquiries();

        if ( $model->load(Yii::$app->request->post()) ) {

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', serialize($model->errors));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DtEnquiries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $model DtEnquiries */
        $model = $this->findModel($id);
        $usr = User::findOne(Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->employee_id = $usr->employee->id;
            $model->employee_name = $usr->employee->snp;
            if ($model->save())
                Yii::$app->session->setFlash('success', 'Сохранено!');
            else
                Yii::$app->session->setFlash('error', serialize($model->errors));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаление документа Заявка на оборудование и его табличной части
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        DtEnquiryDevices::deleteAll(['dt_enquiries_id' => $model->id]);
        if ($model->delete()) {
        } else Yii::$app->session->setFlash('error', 'Ошибка при удалении документа');


        return $this->redirect(['index']);
    }

    /**
     * "Сохряняем" докуент заявки
     * @param integer $id идентификатор документа
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSave($id){
        /* @var $model DtEnquiries */
        $err = false;
        $model = $this->findModel($id);

        if ($model->memo) {
            $key = md5('dt-enquiries' . $model->id);
            $sсan = Images::getLinkfile($key);
            if ($sсan) {
                //если требуется служебка и она загружена то продолжаем
            } else {
                $err = true;
                Yii::$app->session->setFlash('error', 'Если есть служебка то необходимо загрузить её скан!');
            }
        }

        if (!$err){
            $model->status = DtEnquiries::DTE_SAVED;
            if ($model->save()) {
                DtEnquiryDevices::updateAll(['status' => 3], ['dt_enquiries_id' => $id, 'status' => 2]);
                Tasks::Create(Yii::$app->user->id, 'Запросить счет по заявке №' . $id, 1,
                    "Вы создали и сохранили документ " . Html::a('Заявка на устройства №' . $id, ['dt-enquiries/view', 'id' => $id]) . ". \r\n
                        Теперь вам необходимо установить зарезервированное оборудование и запросить счет у поставщика.", 'DtEnquiries', $id
                );
            }
            else Yii::$app->session->setFlash('error', serialize($model->errors));
        };
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Отменяем "Сохранение" документа.
     * Меняем статусы у устройств, удаляем соответствующую задачу/сообщение
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUnSave($id){
        $model = $this->findModel($id);
        $model->status = DtEnquiries::DTE_NEW;
        if ($model->save()) {
            DtEnquiryDevices::updateAll(['status' => 2], ['dt_enquiries_id' => $id, 'status' => 3]);
            Tasks::deleteAll(['target' => 'DtEnquiries', 'target_id' => $id]);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Устанавливаем номер рабочего места для заказываемого устройства
     * @param $id
     * @param $id_wp
     * @throws NotFoundHttpException
     */
    public function actionSetDeviceWp($id, $id_wp){
        /** @var DtEnquiryDevices $model */
        $model = DtEnquiryDevices::findOne($id);
        $model->workplace_id = $id_wp;
        $model->save();
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndexAgree($id) {
        $model = $this->findModel($id);
        $search_di = new DtInvoicesSearch();
        $provider_di = $search_di->search(Yii::$app->request->queryParams, $model->invoices);
        return $this->render('index_agree', [
            'model' => $model,
            'provider' => $provider_di
        ]);
    }

    /**
     * Finds the DtEnquiries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtEnquiries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtEnquiries::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
