<?php

namespace backend\controllers;

use Yii;
use backend\models\WpOwners;
use backend\models\WpOwnersSearch;
use backend\models\StoryWorkplace;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WpownersController implements the CRUD actions for WpOwners model.
 */
class WpownersController extends Controller
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
     * Lists all WpOwners models.
     * @return mixed
     */
    public function actionIndex($model = 0, $dev_id = 0)
    {
        $searchModel = new WpOwnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WpOwners model.
     * @param integer $workplace_id
     * @param integer $employee_id
     * @return mixed
     */
    public function actionView($workplace_id, $employee_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($workplace_id, $employee_id),
        ]);
    }

    /**
     * Creates a new WpOwners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpOwners();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'workplace_id' => $model->workplace_id, 'employee_id' => $model->employee_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Добавляем ответственного за рабочее место
     * @param $id_empl
     * @param $id_wp
     * @return \yii\web\Response
     */
    public function actionAdduser($id_empl, $id_wp){

        $wp_owner = new WpOwners();
        $wp_owner->workplace_id = $id_wp;
        $wp_owner->employee_id = $id_empl;
        $wp_owner->event = true;

        if ($wp_owner->save()) {
            $story = new StoryWorkplace();
            $story->id_wp = $id_wp;
            $story->id_employee = $id_empl;
            $story->event = 1;
            $story->save();
        }
        return $this->redirect(['workplaces/view', 'id' => $id_wp]);
    }

    /**
     * @param $workplace_id
     * @param $employee_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDirectwp($workplace_id, $employee_id) {
        $model_curr = $this->findModel($workplace_id, $employee_id);
        $model_direct = WpOwners::findOne(['employee_id' => $employee_id, 'status' => 1]);
        if ($model_direct) {
            $model_direct->status = 2;
            $model_direct->save();
        }
        $model_curr->status = 1;
        if ($model_curr->save())
            return $this->redirect(['workplaces/view', 'id' => $workplace_id]);
        else {
            Yii::$app->session->setFlash('error', 'Ошибка при сохранении...');
            return $this->redirect('');
        }
    }

    /**
     * Updates an existing WpOwners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $workplace_id
     * @param integer $employee_id
     * @return mixed
     */
    public function actionUpdate($workplace_id, $employee_id)
    {
        $model = $this->findModel($workplace_id, $employee_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'workplace_id' => $model->workplace_id, 'employee_id' => $model->employee_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WpOwners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $workplace_id
     * @param integer $employee_id
     * @return mixed
     */
    public function actionDelete($workplace_id, $employee_id, $id_wp = 0)
    {
        $wp_owner = $this->findModel($workplace_id, $employee_id);

        if ($wp_owner->delete()) {
            $story = new StoryWorkplace();
            $story->id_wp = $workplace_id;
            $story->id_employee = $employee_id;
            $story->event = 0;
            $story->save();
        }

        if ($id_wp == 0) {
            return $this->redirect(['index']);
        } else {
            return $this->redirect(['workplaces/view', 'id' => $id_wp]);
        }
    }

    /**
     * Finds the WpOwners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $workplace_id
     * @param integer $employee_id
     * @return WpOwners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($workplace_id, $employee_id)
    {
        if (($model = WpOwners::findOne(['workplace_id' => $workplace_id, 'employee_id' => $employee_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая сраница недоступна');
        }
    }
}
