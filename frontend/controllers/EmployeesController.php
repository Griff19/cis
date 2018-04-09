<?php

/**
 * Контроллер Сотрудника для frontend
 */

namespace frontend\controllers;

use Yii;
use backend\models\CellnumbersSearch;
use backend\models\EmailsSearch;
use backend\models\Employees;
use backend\models\EmployeesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * EmployeesController implements the CRUD actions for Employees model.
 */
class EmployeesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Отображаем список пользователей.
     * В зависимости от режима либо просто выводится список,
     * либо предоставляется возможжность выбора сотрудника для рабочего места.
     * @return mixed
     */
    public function actionIndex($mode = null, $id_wp = null, $pag = 1)
    {
        $searchModel = new EmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mode' => $mode,
            'id_wp' => $id_wp,
            'pag' => $pag
        ]);
    }

    /**
     * Displays a single Employees model.
     * @param integer $id
     * @param null $mode
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $mode = null)
    {
        $cellSearch = new CellnumbersSearch();
        $cellProvider = $cellSearch->search(Yii::$app->request->queryParams, $id);

        $emailSearch = new EmailsSearch();
        $emailProvider = $emailSearch->search(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'cellProvider' => $cellProvider,
            'cellSearch' => $cellSearch,
            'emailProvider' => $emailProvider,
            'emailSearch' => $emailSearch,
            'mode' => $mode
        ]);
    }

    /**
     * Finds the Employees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница недоступна.');
        }
    }
}
