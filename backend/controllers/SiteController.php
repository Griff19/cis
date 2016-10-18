<?php
namespace backend\controllers;

use backend\models\AdminEmployeesSearch;
use backend\models\AdminWorkplacesSearch;
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
                        'actions' => ['admin', 'admin_workplace'],
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
     * @return string
     */
    public function actionAdmin()
    {
        return $this->render('index');
    }

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
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
