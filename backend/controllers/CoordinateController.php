<?php

namespace backend\controllers;

use Yii;
use backend\models\Coordinate;
use backend\models\CoordinateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CoordinateController implements the CRUD actions for Coordinate model.
 */
class CoordinateController extends Controller
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
     * Lists all Coordinate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoordinateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coordinate model.
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
	 * Функция опрделеляет нужно создать новые координаты или обновить старые
	 * @param $id_wp
	 * @return \yii\web\Response
	 */
    public function actionSetCoord($id_wp, $branch)
    {
        $model = Coordinate::findOne(['workplace_id' => $id_wp]);
        if ($model) {
            return $this->redirect(['update', 'id' => $model->id, 'branch' => $branch]);
        } else {
            return $this->redirect(['create', 'id_wp' => $id_wp, 'branch' => $branch]);
        }
    }

	/**
	 * Добавляем новую точку на карту.
	 * При совпадении идентификатора рабочего места - старая точка удаляется
	 *
	 * @param null $id_wp - идентификатор рабочего места
	 * @param int  $floor -  номер этажа
	 * @param int  $branch - идентификатор филиала
	 * @param int  $mod - режим работы (определяет место возврата)
	 *
	 * @return mixed
	 */
    public function actionCreate($id_wp = null, $floor = 1, $branch = 1, $mod = 0)
    {
        $model = new Coordinate();
        $model->workplace_id = $id_wp;
        $model->floor = $floor;
        $model->branch_id = $branch;
		$old_model = Coordinate::findOne(['workplace_id' => $id_wp]);

		$allCoord = (new CoordinateSearch())->search(Yii::$app->request->queryParams, $floor, $branch);

		if ($model->load(Yii::$app->request->post())) {
	        $model->preset = trim($model->preset);
	        $model->content = trim($model->content);
			if ($model->save())
	        {
		        if ($old_model) {$old_model->delete();}
	        	if ($mod == 1 || $id_wp == null) {
			        return $this->redirect(['index']);
		        } elseif ($mod == 2){
					return $this->redirect(['workplaces/list-unset']);
		        } else {
			        return $this->redirect(['workplaces/view', 'id' => $model->workplace_id]);
		        }
	        } else {
				Yii::$app->session->setFlash('error', serialize($model->errors));
	        }
        } else {
            return $this->render('create', [
                'model' => $model,
	            'allCoord' => $allCoord,
	            'mod' => $mod
            ]);
        }
    }

    /**
     * Изменяем метку
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $mod = 0, $floor = 0)
    {
        $model = $this->findModel($id);
        if ($floor > 0)
        	$model->floor = $floor;
        $allCoord = (new CoordinateSearch())->search(Yii::$app->request->queryParams, $floor);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save())
            {
                if ($mod == 1) {
                    return $this->redirect(['index']);
                } elseif ($mod == 2){
                    return $this->redirect(['workplaces/list-unset']);
                } else {
                    return $this->redirect(['workplaces/view', 'id' => $model->workplace_id]);
                }
            } else {
                Yii::$app->session->setFlash('error', serialize($model->errors));
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'allCoord' => $allCoord,
                'mod' => $mod,
                'floor' => $floor
            ]);
        }
    }

    /**
     * Deletes an existing Coordinate model.
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
     * Finds the Coordinate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coordinate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coordinate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
