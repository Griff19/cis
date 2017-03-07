<?php

namespace backend\controllers;

use backend\models\DtEnquiryWorkplaces;
use backend\models\DtInvoices;
use Yii;
use backend\models\Devices;
use backend\models\DtEnquiryDevices;
use backend\models\DtEnquiryDevicesSearch;
use backend\models\DtEnquiryWorkplacesSearch;
use backend\models\DtDefsheetDevices;
use backend\models\DtEnquiries;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Контроллер обрабатывает запросы к табличной части документа Заявка на оборудование
 */
class DtEnquiryDevicesController extends Controller
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
     * Lists all DtEnquiryDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtEnquiryDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Формируем содержимое для модального окна с выбором устройств в Заявке
     * @return string
     */
    public function actionIndexInvoices($id){

        $searchModel = new DtEnquiryDevicesSearch();
        $dataProvider = $searchModel->searchDevices(Yii::$app->request->queryParams);

        return $this->renderAjax('index_invoices', [
            'dt_invoice_id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DtEnquiryDevices model.
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
     * Добавляем новую строку в табличную часть документа Заявка на оборудование
     * Устройство добавляемтся по типу.
     * Идентификатор устройства получаем из devices/index
     * @param null $id_doc
     * @param null $id
     * @param null $param имеет значения при вызове из таблицы
     * @return mixed
     * @internal param int $id идентификатор устройства
     */
    public function actionCreateNew($id_doc = null, $id = null, $param = null)
    {
        $type = null;
        $model = new DtEnquiryDevices();
        $dev_def = null;
        if ($param) {
            parse_str($param, $arr);
            //$id_doc = ArrayHelper::getValue($arr, 'id_doc'); //идентификатор документа заявки
            $type = ArrayHelper::getValue($arr, 'type'); //тип устройства
            $dev_id = ArrayHelper::getValue($arr, 'dev_id'); //идентификатор списанного устройства
            $id_def = ArrayHelper::getValue($arr, 'id_def'); //строка таблицы списанных устройств
            /** @var $dev_def DtDefsheetDevices */
            $dev_def = DtDefsheetDevices::findOne(['id_def' => $id_def]);
            if ($dev_def) {
                $model->dt_def_dev_id = $id_def;
                $model->workplace_id = $dev_def->workplace_id;
            }
        }

        $model->dt_enquiries_id = $id_doc;
        $model->parent_device_id = $id;
        $model->type_id = $type;
        if (empty($model->workplace_id)) {
            $arr = DtEnquiryWorkplaces::arrWpIds($id_doc);
            if ($arr)
                $model->workplace_id = $arr[0]['workplace_id'];
            else {
                Yii::$app->session->setFlash('error', 'Необходимо добавить рабочее место');
                return $this->redirect(['dt-enquiries/view', 'id' => $id_doc]);
            }
        }

        //$model->note = 'Требуется покупка';
        $model->status = DtEnquiryDevices::NEED_BUY;

        if ($type || $model->load(Yii::$app->request->post())){
            $model->save();

            if ($dev_def) {
                $dev_def->status = 2;
                $dev_def->save();
            }

            return $this->redirect(['dt-enquiries/view', 'id' => $id_doc]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Добавляем строку в табличную часть документа Заявка на оборудование
     * Устройство выбирается из списка устройств на рабочих местах
     * @param $id Devices Идентификатор выбраного устройства
     * @param $param
     * @return string|\yii\web\Response
     */
    public function actionCreate2($id, $param = null)
    {
        /* @var $device Devices */
        if ($param) {}
        else
            $param = Yii::$app->request->queryString;
        parse_str($param, $arr);
        $id_doc = $arr['id_doc'];
        //$dev_id = ArrayHelper::getValue($arr, 'dev_id');
        $id_def = ArrayHelper::getValue($arr, 'id_def');
        $model = new DtEnquiryDevices();
        $device = Devices::findOne($id);

        $model->dt_enquiries_id = $id_doc;
        $model->type_id = $device->type_id;

        $dew = DtEnquiryWorkplaces::findOne(['dt_enquiries_id' => $model->dt_enquiries_id]);
        $model->workplace_id = $dew->workplace_id;
        //нужно отразить в акте списания что устройство на замену подобрано
        //сменить статус

        /* @var $dev_def DtDefsheetDevices */
        $dev_def = DtDefsheetDevices::findOne(['id_def' => $id_def]);

        if ($device->parent_device_id)
            $model->parent_device_id = $device->parent_device_id;
        $model->device_id = $id;
        if ($dev_def) {
            $model->dt_def_dev_id = $id_def;
            $model->workplace_id = $dev_def->workplace_id;
            $dev_def->status = 2;
        }
        $model->note = 'Зарезервировано на складе';
        $model->status = DtEnquiryDevices::RESERVED;

        if ($model->save()) {
            $device->fake_device = 2;
            $device->save();
            if ($dev_def) $dev_def->save();
        } else Yii::$app->session->setFlash('error', 'Ошибка при добавлении устройства');

        if ($id_doc > 0){
            return $this->redirect(['dt-enquiries/view', 'id' => $id_doc]);
        } else {
            return $this->render('create', ['model' => $model]);
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
     * Удаляем строку из табличной части документа Заявка на оборудование
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_doc = $model->dt_enquiries_id;
        $id_def = $model->dt_def_dev_id;
        //var_dump($model); die;
        DtDefsheetDevices::updateAll(['status' => 1], ['id_def' => $id_def]);

        /* @var $device Devices */
        //При удалении строки, снимаем устройство с резерва
        $device = Devices::findOne($model->device_id);
        if ($device) {
            $device->fake_device = 0;
            $device->save();
        }
        if ($model->delete()) {} else
            Yii::$app->session->setFlash('error', 'Ошибка при удалении строки');

        return $this->redirect(['dt-enquiries/view', 'id' => $id_doc]);
    }

    /**
     * Finds the DtEnquiryDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtEnquiryDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtEnquiryDevices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
