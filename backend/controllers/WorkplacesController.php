<?php

namespace backend\controllers;

use backend\models\Devices;
use Yii;
use backend\models\Workplaces;
use backend\models\WorkplacesSearch;
use backend\models\WpOwnersSearch;
use backend\models\DevicesSearch;
use backend\models\VoipnumbersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * WorkplacesController implements the CRUD actions for Workplaces model.
 */
class WorkplacesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'view', 'list', 'get-owner-id', 'select'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create','update','delete', 'readfile', 'uploadform', 'list-unset'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
	                [
	                    'actions' => ['index', 'view', 'create', 'list'],
		                'allow' => true,
		                'roles' => ['auditor'],
	                ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Workplaces models.
     * @param null $mode режим отражения таблицы рабочих мест 'sel' - выбор РМ
     * @param null $id_dev идентификатор устройства для которого выбираем РМ
     * @param null $target страница с которой был вызов метода (при выборе рабочего места)
     * @param null $target_id идентификатор элемента для которого вызван метод (при выборе рабочего места)
     * @return mixed
     */
    public function actionIndex($mode = null, $id_dev = null, $target = null, $target_id = null)
    {
        if ($mode == 'sel') {
            /* @var $device Devices */
            $device = Devices::findOne($id_dev);
            if ($device->fake_device != Devices::DEVICE_DEF) {
                Yii::$app->session->setFlash('error', 'Операция не выполнена. Устройство зарезервировано либо уже перемещается.');
                return $this->redirect(['devices/view', 'id' => $id_dev]);
            }
        }

        $searchModel = new WorkplacesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'target' => $target,
            'target_id' => $target_id,
            'mode' => $mode,
            'id_dev' => $id_dev
        ]);
    }

	/**
	 * Выводим подробности по рабочему месту.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //Аудитору запрещаем просмотр Буланихи
        if (Yii::$app->user->can('auditor') && $model->branch_id == 1){
	        throw new NotFoundHttpException('Запрашиваемая страница не доступна.');
        }
    	$searchEmployeeModel = new WpOwnersSearch();
        $employeeProvider = $searchEmployeeModel->search(Yii::$app->request->queryParams, $id);

        $searchDeviceModel = new DevicesSearch();
        $deviceProvider = $searchDeviceModel->searchDeviceOnWp(Yii::$app->request->queryParams, $id);

        $searchVoip = new VoipnumbersSearch();
        $voipProvider = $searchVoip->search(Yii::$app->request->queryParams, 0, $id);

        return $this->render('view', [
            'model' => $model,
            'employeeProvider' => $employeeProvider,
            'searchDeviceModel' => $searchDeviceModel,
            'deviceProvider' => $deviceProvider,
            'voipProvider' => $voipProvider
        ]);
    }

    /**
     * Creates a new Workplaces model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Workplaces();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Workplaces model.
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
     * Deletes an existing Workplaces model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Готовим список рабочих мест по идентификатору кабинета для вывода в поле ввода
     * @param $id
     */
    public static function actionList($id){

        $countWp = Workplaces::find()->where(['room_id' => $id])->count();

        if ($countWp > 0) {
            $workplaces = Workplaces::find()->where(['room_id' => $id])->all();
            //echo '<option value="#">Выберите рабочее место...</option>';
            foreach ($workplaces as $workplace) {
                echo '<option value="' . $workplace->id.'">' . $workplace->workplaces_title . '</option>';
            }
        } else {
            echo '<option value="0"> - </option>';
        }
    }

    /**
     * Функция выбора рабочего места
     * Выбор должен быть универсальный для любого
     * @param $id int идентификатор рабочего места
     * @param $target string контроллер и экшн назначения
     * @param $target_id int идентификатор назначения
     * @return \yii\web\Response
     */
    public function actionSelect($id, $target, $target_id, $id_dev = null){

        switch ($target){
            case 'dt-enquiry-workplaces/create':
            case 'devices/addtowp':
                $options = [$target, 'id' => $target_id, 'id_wp' => $id];
                break;
            case 'tmp-moving/create':
                $options = [$target, 'device_id' => $id_dev, 'workplace_from' => $target_id, 'workplace_where' => $id];
                break;
            default:
                return false;
        }
        
        return $this->redirect($options);
    }

    /**
     * Получаем идентификатор ответственного
     * @param $id
     * @return int
     */
    public static function actionGetOwnerId($id){
        /* @var $model Workplaces */
        $arr = [];
        $model = Workplaces::findOne($id);
        $arr[] = $model->owner[0]->id;
        $arr[] = $model->owner[0]->snp;
        $res = json_encode($arr);
        return $res;
    }

    public function actionListUnset()
    {
    	$search = new WorkplacesSearch();
    	$data = $search->searchUnsetCoordinate(Yii::$app->request->queryParams);

    	return $this->render('unset_coord', [
    		'dataProvider' => $data,
		    'searchModel' => $search,
	    ]);
    }
    /**
     * Finds the Workplaces model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Workplaces the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workplaces::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
