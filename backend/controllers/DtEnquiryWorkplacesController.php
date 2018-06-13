<?php

namespace backend\controllers;

use backend\models\DtDefsheetDevices;
use backend\models\DtEnquiryDevices;
use Yii;
use backend\models\DtEnquiryWorkplaces;
use backend\models\DtEnquiryWorkplacesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DtEnquiryWorkplacesController implements the CRUD actions for DtEnquiryWorkplaces model.
 */
class DtEnquiryWorkplacesController extends Controller
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
     * Lists all DtEnquiryWorkplaces models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtEnquiryWorkplacesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DtEnquiryWorkplaces model.
     * @param integer $dt_enquiries_id
     * @param integer $workplace_id
     * @return mixed
     */
    public function actionView($dt_enquiries_id, $workplace_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($dt_enquiries_id, $workplace_id),
        ]);
    }

    /**
     * Добавляем рабочее место к документу Заявка на оборудование
     * @return mixed
     */
    public function actionCreate($id, $id_wp)
    {
        $model = new DtEnquiryWorkplaces();
        $model->dt_enquiries_id = $id;
        $model->workplace_id = $id_wp;
        if ($model->save())
            Yii::$app->session->setFlash('success', 'Рабочее место успешно добавлено');
        else
            Yii::$app->session->setFlash('error', serialize($model->errors));
        return $this->redirect(['dt-enquiries/view', 'id' => $id]);

    }

    /**
     * Updates an existing DtEnquiryWorkplaces model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $dt_enquiries_id
     * @param integer $workplace_id
     * @return mixed
     */
    public function actionUpdate($dt_enquiries_id, $workplace_id)
    {
        $model = $this->findModel($dt_enquiries_id, $workplace_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dt_enquiries_id' => $model->dt_enquiries_id, 'workplace_id' => $model->workplace_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаляем рабочее место из списка привязанных к документу
     * @param integer $dt_enquiries_id
     * @param integer $workplace_id
     * @return mixed
     */
    public function actionDelete($dt_enquiries_id, $workplace_id)
    {
        $model = $this->findModel($dt_enquiries_id, $workplace_id);
        DtDefsheetDevices::updateAll(['status' => 1], ['workplace_id' => $workplace_id, 'status' => 2]);
        DtEnquiryDevices::deleteAll(['dt_enquiries_id' => $dt_enquiries_id, 'workplace_id' => $workplace_id]);
        $model->delete();
        return $this->redirect(['dt-enquiries/view', 'id' => $dt_enquiries_id]);
    }

    /**
     * Finds the DtEnquiryWorkplaces model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $dt_enquiries_id
     * @param integer $workplace_id
     * @return DtEnquiryWorkplaces the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dt_enquiries_id, $workplace_id)
    {
        if (($model = DtEnquiryWorkplaces::findOne(['dt_enquiries_id' => $dt_enquiries_id, 'workplace_id' => $workplace_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
