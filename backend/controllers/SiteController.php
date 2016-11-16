<?php
namespace backend\controllers;

use app\models\DtInvoiceDevicesSearch;
use backend\models\AdminEmployeesSearch;
use backend\models\AdminWorkplacesSearch;
use backend\models\DtEnquiryDevicesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use backend\models\StartEmployeesSearch;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['admin', 'admin_workplace', 'employee-it'],
                        'allow' => true,
                        'roles' => ['it']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Стандартная главная страница
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new StartEmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('start', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Открываем страницу с кнопками основных действий
     * @return string
     */
    public function actionAdmin()
    {
        return $this->render('index');
    }

    /**
     * Основная страница сотрудника отдела IT
     * @return string
     */
    public function actionEmployeeIt(){
        //собираем данные по устройствам в документах "Счет"
        $search_did = new DtInvoiceDevicesSearch();
        $provider_did = $search_did->search(Yii::$app->request->queryParams);
        //собираем данные по устройствам в документах "Заявка на оборудование"
        $search_ded = new DtEnquiryDevicesSearch();
        $provider_ded = $search_ded->searchDevices(Yii::$app->request->queryParams);
        return $this->render('it_index', [
            'search_did' => $search_did,
            'provider_did' => $provider_did,
            'search_ded' => $search_ded,
            'provider_ded' => $provider_ded
        ]);
    }

    /**
     * Генерируем страницу для сотрудника ит-отдела
     */
    public function actionIt(){
        return $this->render('it_index');
    }

    /**
     * Страница с формой поиска рабочих мест и сотрудников
     * @param int $tab
     * @return string
     */
    public function actionAdmin_workplace($tab = 1){
        $workplaceSearch = new AdminWorkplacesSearch();
        $workplaceProvider = $workplaceSearch->search(Yii::$app->request->queryParams);

        $employeeSearch = new AdminEmployeesSearch();
        $employeeProvider = $employeeSearch->search(Yii::$app->request->queryParams);

        return $this->render('admin_workplace', [
            'workplaceProvider' => $workplaceProvider,
            'workplaceSearch' => $workplaceSearch,
            'employeeProvider' => $employeeProvider,
            'employeeSearch' => $employeeSearch,
            'tab' => $tab
        ]);
    }

    /**
     * Вход пользователя
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Выход пользователя
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
