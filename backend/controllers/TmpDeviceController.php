<?php

namespace backend\controllers;

use Yii;
use backend\models\TmpDevice;
use backend\models\TmpDeviceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TmpDeviceController implements the CRUD actions for TmpDevice model.
 */
class TmpDeviceController extends Controller
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
     * Lists all TmpDevice models.
     * @return mixed
     */
    public function actionIndex($id_twp = 0)
    {
        $searchModel = new TmpDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TmpDevice model.
     * @param integer $tmp_workplace_id
     * @param integer $devices_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($tmp_workplace_id, $devices_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($tmp_workplace_id, $devices_id),
        ]);
    }

    /**
     * Creates a new TmpDevice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TmpDevice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'tmp_workplace_id' => $model->tmp_workplace_id, 'devices_id' => $model->devices_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TmpDevice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $tmp_workplace_id
     * @param integer $devices_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($tmp_workplace_id, $devices_id)
    {
        $model = $this->findModel($tmp_workplace_id, $devices_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'tmp_workplace_id' => $model->tmp_workplace_id, 'devices_id' => $model->devices_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TmpDevice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $tmp_workplace_id
     * @param integer $devices_id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($tmp_workplace_id, $devices_id)
    {
        $this->findModel($tmp_workplace_id, $devices_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TmpDevice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $tmp_workplace_id
     * @param integer $devices_id
     * @return TmpDevice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tmp_workplace_id, $devices_id)
    {
        if (($model = TmpDevice::findOne(['tmp_workplace_id' => $tmp_workplace_id, 'devices_id' => $devices_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
