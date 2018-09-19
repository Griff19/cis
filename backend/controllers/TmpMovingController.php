<?php

namespace backend\controllers;

use backend\models\Devices;
use Yii;
use backend\models\TmpMoving;
use backend\models\TmpMovingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TmpMovingController implements the CRUD actions for TmpMoving model.
 */
class TmpMovingController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['sysadmin'],
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
     * Lists all TmpMoving models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TmpMovingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TmpMoving model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TmpMoving model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $device_id
     * @param $workplace_from
     * @param null $workplace_where
     * @return mixed
     */
    public function actionCreate($device_id, $workplace_from, $workplace_where = null)
    {
        /* @var $device Devices */
        $device = Devices::findOne($device_id);
        if ($device->fake_device != Devices::DEVICE_DEF) {
            Yii::$app->session->setFlash('error', 'Операция не выполнена. Устройство в резерве либо уже перемещается.');
            return $this->redirect(['devices/view', 'id' => $device_id]);
        }
        $model = new TmpMoving();
        $model->device_id = $device_id;
        $model->workplace_from = $workplace_from;
        $model->workplace_where = $workplace_where;
        $model->user_id = Yii::$app->user->id;
        $model->status = $model::STATUS_DEFAULT;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $device->fake_device = Devices::DEVICE_RESERVED;
            $device->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TmpMoving model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
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
     * Deletes an existing TmpMoving model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TmpMoving model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TmpMoving the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TmpMoving::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
