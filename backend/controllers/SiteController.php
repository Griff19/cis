<?php
namespace backend\controllers;

use backend\models\DtInvoicesPayment;
use backend\models\DtInvoicesPaymentSearch;
use Yii;
use backend\models\DtInvoiceDevicesSearch;
use backend\models\AdminEmployeesSearch;
use backend\models\AdminWorkplacesSearch;
use backend\models\DtEnquiriesSearch;
use backend\models\DtEnquiryDevicesSearch;
//use backend\models\DtInvoices;
use backend\models\DtInvoicesSearch;
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
                        'actions' => ['admin', 'admin_workplace', 'employee-it', 'set-status-payment'],
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
        //собираем документы "Заявка на оборудование"
        $search_de = new DtEnquiriesSearch();
        $provider_de = $search_de->search(Yii::$app->request->queryParams);
        //собираем документы "Счет"
        $search_di = new DtInvoicesSearch();
        $provider_di = $search_di->search(Yii::$app->request->queryParams);
        //собираем данные по устройствам в документах "Счет"
        $search_did = new DtInvoiceDevicesSearch();
        $provider_did = $search_did->searchToEmployee(Yii::$app->request->queryParams);
		//$provider_did = $search_did->search(Yii::$app->request->queryParams);
        //собираем данные по устройствам в документах "Заявка на оборудование"
        $search_ded = new DtEnquiryDevicesSearch();
        $provider_ded = $search_ded->searchDevices(Yii::$app->request->queryParams);
		//собираем данные по оплатам счетов
		$search_dip = new DtInvoicesPaymentSearch(['scenario' => 'to_employee']);
		$provider_dip = $search_dip->searchPayments(Yii::$app->request->queryParams);
        return $this->render('it_index', [
            'search_de' => $search_de,
            'provider_de' => $provider_de,
            'search_di' => $search_di,
            'provider_di' => $provider_di,
            'search_did' => $search_did,
            'provider_did' => $provider_did,
            'search_ded' => $search_ded,
            'provider_ded' => $provider_ded,
			'search_dip' => $search_dip,
			'provider_dip' => $provider_dip
        ]);
    }

    /**
     * Генерируем страницу для сотрудника ит-отдела
     */
    public function actionIt(){
        return $this->render('it_index');
    }

    /**
     * Устанавливаем статус платежа
     *
     * @param int $id Идентификатор платежа
     * @param int $status Устанавливаемый статус платежа
     * @return \yii\web\Response
     */
	public function actionSetStatusPayment($id, $status){
		$model = DtInvoicesPayment::findOne($id);
		$model->scenario = 'update';
		$model->status = $status;
		if (!$model->save())
            Yii::$app->session->setFlash('error', serialize($model->getErrors()));

		return $this->actionEmployeeIt();
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
