<?php

namespace backend\controllers;

use Yii;
use backend\models\CellnumbersSearch;
use backend\models\EmailsSearch;
use backend\models\Employees;
use backend\models\EmployeesSearch;
use backend\models\Branches;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * EmployeesController implements the CRUD actions for Employees model.
 */
class EmployeesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create','update','delete', 'readfile', 'uploadform'],
                        'allow' => true,
                        'roles' => ['admin'],
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
     * @return mixed
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
     * Creates a new Employees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_wp = 0)
    {
        $model = new Employees(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post())) {

            $model->snp = $model->surname . ' ' . $model->name . ' ' . $model->patronymic;
            $model->save();
            if ($id_wp == 0) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['index', 'mode'=> 'select', 'id_wp' => $id_wp]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Загружаем данные по сотрудникам из файла.
     * Поле snp используется для заполнения полей name, surname и patronymic
     * сейчас не используется, вместо этого работает скрипт Auto.php
     * @return \yii\web\Response
     */
    public function actionReadfile(){
        $filename = 'in/employees.txt';
        $readfile = fopen($filename, 'r');
        while ($str = fgets($readfile, 1024)){

            $items = explode(chr(9), $str); //ФИО; Код; Регион; Должность; Примечание; Код Контрагента
            if (count($items) != 6) {Yii::$app->session->setFlash('error', 'Файл не соответствует формату!'); break;}
            if ($items[1] == 'Код') continue;
            //var_dump($items);
            //continue;
            $emp = Employees::findOne(['snp' => $items[0]]);

            if(isset($emp)) {
                // если в базе есть ФИО то пропускаем (т.е. загрузка не рассчитана на полных тёзок)
            } else {
                $emp = new Employees();
                $emp->snp = $items[0];

                $snp = explode(" ", $items[0]);
                //var_dump($snp); continue;
                $emp->surname = $snp[0];
                $emp->name = $snp[1];
                $emp->patronymic = $snp[2];

                $emp->employee_number = $items[1];
                $branch_id = Branches::getIdByName($items[2]);
                if ($branch_id > 0) {
                    $emp->branch_id = $branch_id;
                } else {
                    $emp->branch_id = 0; //Буланиха
                }
                $emp->job_title = $items[3];
                $emp->unique_1c_number = $items[5];
                $emp->save();
            }
        }
        fclose($readfile);
        //die;

        return $this->redirect(['index']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionUploadform(){
        $model = new Employees();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->file){
                $fileName = 'employees';
                $model->file->saveAs('in/'.$fileName.'.'.$model->file->extension);
                $this->actionReadfile();
                //die;
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload' => true,
            ]);
        }
    }

    /**
     * Редактирование существующего сотрудника.
     * ФИО хранится в таблице в поле snp одной строкой.
     * При редактировании полей name, surname, patronymic они либо берутся из формы, либо из строки snp.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ( $model->load(Yii::$app->request->post()) ) {
            $snp = explode(" ", $model->snp); //разбиваем фио по пробелам

            if (empty($model->surname)) {
                $model->surname = $snp[0]; //получаем фамилию
            } else {$snp[0] = $model->surname;}
            if (empty($model->name)) {
                $model->name = $snp[1]; //получаем имя
            } else {$snp[1] = $model->name;}
            if (empty($model->patronymic)) {
                $model->patronymic = $snp[2]; //получаем отчество
            } else {$snp[2] = $model->patronymic;}
            $model->snp = $snp[0] . ' ' . $snp[1] . ' ' . $snp[2];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Employees model.
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
