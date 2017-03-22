<?php

namespace backend\controllers;

use backend\models\DPartnerContactsSearch;
use backend\models\DPartnerContractsSearch;
use Yii;
use backend\models\DPartners;
use backend\models\DPartnersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DPartnersController implements the CRUD actions for DPartners model.
 */
class DPartnersController extends Controller
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
     * Lists all DPartners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DPartnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Открываем контрагента
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dp_cont_search = new DPartnerContactsSearch();
        $dp_cont_provider = $dp_cont_search->search(Yii::$app->request->queryParams, $id);

        $dp_contr_search = new DPartnerContractsSearch();
        $dp_contr_provider = $dp_contr_search->search(Yii::$app->request->queryParams, $id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dp_cont_search' => $dp_cont_search,
            'dp_cont_provider' => $dp_cont_provider,
            'dp_contr_search' => $dp_contr_search,
            'dp_contr_provider' => $dp_contr_provider
        ]);
    }

    /**
     * Создаем нового контрагента
     * @return mixed
     */
    public function actionCreate($target = '', $target_id = '')
    {
        $model = new DPartners();

        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->save())
                if ($target)
                    return $this->redirect([$target, 'enquiry_id' => $target_id]);
                else
                    return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DPartners model.
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
     * Deletes an existing DPartners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DPartners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DPartners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DPartners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
