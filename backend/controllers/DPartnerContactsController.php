<?php

namespace backend\controllers;

use Yii;
use backend\models\DPartnerContacts;
use backend\models\DPartnerContactsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DPartnerContactsController implements the CRUD actions for DPartnerContacts model.
 */
class DPartnerContactsController extends Controller
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
     * Lists all DPartnerContacts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DPartnerContactsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DPartnerContacts model.
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
     * Добавить новый контакт контрагенту.
     * После выполнения возвращаем страницу контрагента.
     * @return mixed
     */
    public function actionCreate($partner_id)
    {
        $model = new DPartnerContacts();
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
     * Редактировать контакт
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['d-partners/view', 'id' => $model->partner_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удалить контакт контрагента
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
     * Finds the DPartnerContacts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DPartnerContacts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DPartnerContacts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
