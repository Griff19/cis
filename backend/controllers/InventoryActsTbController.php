<?php

namespace backend\controllers;

use backend\models\InventoryActs;
use backend\models\Reports;
use backend\models\Workplaces;
use Yii;
use backend\models\InventoryActsTb;
use backend\models\InventoryActsTbSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InventoryActsTbController implements the CRUD actions for InventoryActsTb model.
 */
class InventoryActsTbController extends Controller
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
     * Lists all InventoryActsTb models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryActsTbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InventoryActsTb model.
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
     * Создаем строку в табличной части документа Акт Инвентаризации
     * обновление страницы происходит через AJAX
     * @return mixed
     */
    public function actionCreate($act_id, $dev_id, $id_wp = null, $aux = null, $status)
    {
        $count = InventoryActsTb::find()->where(['act_id' => $act_id, 'device_id' => $dev_id])->count();
        if ($count > 0 && $act_id) {
            InventoryActsTb::deleteAll(['act_id' => $act_id, 'device_id' => $dev_id]);

            //Yii::$app->session->setFlash('error', 'Устройство уже добавлено в текущий Акт Инвентаризации');
            //return $this->redirect(['inventory-acts/view', 'id' => $act_id]);
            $model = InventoryActs::findOne($act_id);
            $iatSearch = new InventoryActsTbSearch();
            $iatProvider = $iatSearch->search(Yii::$app->request->queryParams);
            $devProvider = Reports::getInventoryData($model->workplace_id);
            return $this->render('/inventory-acts/view', [
                'model' => $model,
                'iatSearch' => $iatSearch,
                'iatProvider' => $iatProvider,
                'devProvider' => $devProvider
            ]);
        }

        $model = new InventoryActsTb();
        $model->act_id = $act_id;
        $model->device_id = $dev_id;
        $model->device_workplace_id = $id_wp;
        $model->aux = $aux;
        $model->status = $status;

        if ($model->save()) {
            $model = InventoryActs::findOne($act_id);
            $iatSearch = new InventoryActsTbSearch();
            $iatProvider = $iatSearch->search(Yii::$app->request->queryParams);
            $devProvider = Reports::getInventoryData($model->workplace_id);
            return $this->render('/inventory-acts/view', [
                'model' => $model,
                'iatSearch' => $iatSearch,
                'iatProvider' => $iatProvider,
                'devProvider' => $devProvider
            ]);

            //return $this->redirect(['inventory-acts/view', 'id' => $act_id]);
        }
    }

    /**
     * Updates an existing InventoryActsTb model.
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
     * Удаляем строку из табличной части документа Акт Инвентаризации
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $act_id = $model->act_id;
        $model->delete();
        return $this->redirect(['inventory-acts/view', 'id' => $act_id]);
    }

    /**
     * Finds the InventoryActsTb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventoryActsTb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventoryActsTb::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
