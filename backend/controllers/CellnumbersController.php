<?php

namespace backend\controllers;

use Yii;
use backend\models\Employees;
use backend\models\CellNumbers;
use backend\models\CellnumbersSearch;
use common\models\FtpWork;

use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Class CellnumbersController
 * @package backend\controllers
 */
class CellnumbersController extends Controller
{
    public $prog;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create','update','delete', 'direct', 'readfile', 'uploadform', 'dwnftp'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CellNumbers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CellnumbersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CellNumbers model.
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
     * Creates a new CellNumbers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($emp_id = null)
    {
        $model = new CellNumbers();
        $model->employee_id = $emp_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($emp_id == null)
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return $this->redirect(['employees/view', 'id' => $emp_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CellNumbers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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
     * Deletes an existing CellNumbers model.
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
     * Обработка загруженного файла
     * сейчас не используется, вместо этого работает скрипт Auto.php
     * @throws \yii\db\Exception
     */
    public function actionReadFile(){
        $filename = 'in/cellnumbers.txt';
        //$fsz = filesize($filename);

        $readfile = fopen($filename, 'r');
        $err = '';
        //инвертируем статус. номера, не прошедшие обработку, останутся с отрицательным статусом
        Yii::$app->db->createCommand('UPDATE cell_numbers SET status = status*(-1)')->execute();

        while ($str = fgets($readfile, 1024)){
            //$items = explode(chr(9), $str);
            $items = explode(";", $str);
            if (count($items) != 3) {
                Yii::$app->db->createCommand('UPDATE cell_numbers SET status = status*(-1)')->execute(); //возвращаем статусы
                Yii::$app->session->setFlash('error', 'Файл не соответствует формату');
                break;
            }
            if (stristr($str, 'Абонент') > '') continue;

            $str_cell = str_replace('-', '', $items[2]);
            $cell = CellNumbers::findOne(['cell_number' => $str_cell]);
            $emp = Employees::findOne(['snp' => $items[0]]);

            if (isset($cell)){

                if (!isset($emp)) continue;

                if ($cell->employee_id != $emp->id) {
                    $cell_count = CellNumbers::find()->where(['employee_id' => $emp->id])->count();
                    $cell->employee_id = $emp->id;
                    $cell->status = $cell_count + 1;
                    $cell->save();
                } else {
                    $cell->status = $cell->status * (-1);

                    if ($cell->validate()) {
                        $cell->save();
                    } else {
                        //если не прошли валидацию - собираем ошибки
                        foreach ($cell->errors as $key => $error){
                            $err .= '<b>' . $key . '</b> ' . implode('   ', $error) . '<br>';
                        }
                        Yii::$app->session->setFlash('error', $err);
                    }
                }
            } else {
                $cell = new CellNumbers();
                if ($emp) {
                    $cell_count = CellNumbers::find()->where(['employee_id' => $emp->id])->count();
                    $cell->status = $cell_count + 1;
                    $cell->employee_id = $emp->id;
                }
                $cell->cell_number = $str_cell;
                $cell->save();
            }
        }
        //номерам, не попавшим в обработку (статус < 0), удаляем владельцев и сбрасываем статус на 1
        Yii::$app->db
            ->createCommand('UPDATE cell_numbers SET employee_id = NULL, status = 1 WHERE status < 0')
            ->execute();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionUploadform(){
        $model = new CellNumbers();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->file){
                $fileName = 'cellnumbers';
                $model->file->saveAs('in/'.$fileName.'.'.$model->file->extension);
                //$this->actionReadfile();
                $this->actionReadFile();
                //die;
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload' => true,
                'progress' => 0
            ]);
        }
    }

    public function actionDwnftp(){

        $fileloc = 'in/cellnumbers.txt';
        $fileftp = 'itbase/MobilNum.txt';
        //$ftpcatalog = 'itbase/';
        //$localcatalog = 'Acts/ActsDoc';

        $ftp = new FtpWork();
        if ($ftp->download($fileftp, $fileloc)){
            Yii::$app->session->setFlash('success', 'файл скачан');
        } else {
            Yii::$app->session->setFlash('error', 'файл не скачан');
        }
        //$ftp->downloadAll($ftpcatalog, $localcatalog);
        //$this->actionReadFile();

        return $this->redirect(['index']);
    }

    /**
     * назначаем номер "основным" status = 1
     * при этом старый "основной" получает статус текущего номера
     * @param $id
     * @return void|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDirect($id){

        $model = $this->findModel($id);
        $status = $model->status;
        if ($status == 1) return;
        $dir_model = '';
        try {
            $dir_model = $this->findModel(['employee_id' => $model->employee_id, 'status' => 1]);
        } catch (\Exception $e){

        }
        $transaction = CellNumbers::getDb()->beginTransaction();
        try {
            $ex = false;
            if ($dir_model) {
                $dir_model->status = null;
                if (!$dir_model->save()) $ex = true;
            }
            $model->status = 1;
            if (!$model->save()) {$ex = true;}
            if ($dir_model) {
                $dir_model->status = $status;
                if (!$dir_model->save()) $ex = true;
            }
            if ($ex) throw new \Exception('Не удалось совершить действие, возможно ошибка валидации...');

            Yii::$app->session->setFlash('success', 'Номер установлен успешно!');
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['employees/view', 'id' => $model->employee_id]);
    }

    /**
     * Finds the CellNumbers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CellNumbers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CellNumbers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
