<?php

namespace backend\controllers;

use Yii;
use backend\models\DPartnerContracts;
use backend\models\DPartnerContractsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DPartnerContractsController implements the CRUD actions for DPartnerContracts model.
 */
class DPartnerContractsController extends Controller
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
     * Lists all DPartnerContracts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DPartnerContractsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DPartnerContracts model.
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
     * Добавляем контракт контрагенту.
     * После выполнения возвращаем страницу контрагента.
     * @return mixed
     */
    public function actionCreate($partner_id)
    {
        $model = new DPartnerContracts();
        $model->partner_id = $partner_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['d-partners/view', 'id' => $partner_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Редактировать информацию о контракте
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$partner_id = $model->partner_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['d-partners/view', 'id' => $model->partner_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DPartnerContracts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $partner_id = $model->partner_id;
        $model->delete();

        return $this->redirect(['d-partners/view', 'id' => $partner_id]);
    }

    /**
     * Finds the DPartnerContracts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DPartnerContracts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DPartnerContracts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
