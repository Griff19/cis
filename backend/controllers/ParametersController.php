<?php

namespace backend\controllers;

use Yii;
use backend\models\Parameters;
use backend\models\ParametersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParametersController implements the CRUD actions for Parameters model.
 */
class ParametersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Parameters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParametersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parameters model.
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
     * Создаем новый набор параметров для устройства по его id
     * @return mixed
     */
    public function actionCreate($id_dev, $dev_name)
    {
        $model = new Parameters();
        $model->id_device = $id_dev;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['devices/view', 'id' => $id_dev]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dev_name' => $dev_name
            ]);
        }
    }

    /**
     * Редактируем параметры устройства. Если функция вызвана со страницы Рабочего места
     * то через сессию передается значения id_wp - идентификатор рабочего места куда нужно вернутся
     * после редактирования
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (Yii::$app->session->has('id_wp') && Yii::$app->session->get('id_wp') > 0) {
                $id_wp = Yii::$app->session->get('id_wp');
                return $this->redirect(['workplaces/view', 'id' => $id_wp]);
            } else {
                return $this->redirect(['devices/view', 'id' => $model->id_device]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Parameters model.
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
     * Finds the Parameters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parameters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parameters::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Указанный набор параметров не найден');
        }
    }
}
