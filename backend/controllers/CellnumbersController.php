<?php

namespace backend\controllers;

use backend\models\PhoneBill;
use DOMDocument;
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
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['create','update', 'direct'],
                        'allow' => true,
                        'roles' => ['sysadmin'],
                    ],
                    [
                        'actions' => ['delete', 'readfile', 'uploadform', 'dwnftp'],
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
     * @throws NotFoundHttpException
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
     * Deletes an existing CellNumbers model.
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
     *
     */
    public function costInFile(){
        
        $filename = 'in/costofcell.xml';
        if ( file_exists($filename) )
        {
            $str_date = '';
            $dom = DOMDocument::load( $filename );
            $r = 0;
            $rows = $dom->getElementsByTagName( 'Row' );
            foreach ($rows as $row)
            {
                $r++; //Считаем строки чтобы достать дату из 3 строки
                $rb = false;
                $i = 0;
                $str_cell = '';
    
                $subscription = 0;
                $one_time = 0;
                $online = 0;
                $roaming = 0;
                $cost = 0;
                
                $cells = $row->getElementsByTagName( 'Cell' );
                foreach( $cells as $cell )
                {
                    $i++; // Считаем столбцы: Номер в 1, Сумма в 24
                    //Получаем дату счета из 3 строки
                    if ($r == 3){
                        $str_date = substr($cell->textContent, -10);
                    }
                    
                    //^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$ можно использовать такой шаблон для телефона
                    if ($i == 1 && preg_match('/^\d{11}$/', $cell->textContent, $matches) == 1) {
                        $rb = true; // Были получены данные по строке - найден номер телефона
                        $str_cell = substr($cell->textContent, 1);
                    }
                    if ($i == 3 || $i == 4) {
                        $subscription += round($cell->textContent, 2);
                    }
                    if ($i == 5) {
                        $one_time += round($cell->textContent, 2);
                    }
                    if ($i >= 6 && $i <= 19) {
                        $online +=  round($cell->textContent, 2);
                    }
                    if ($i >= 20 && $i <= 23) {
                        $roaming += round($cell->textContent, 2);
                    }
                    if ($i == 24) {
                        $cost = round($cell->textContent, 2);
                    }
                }
                if ($rb){
                    $phone_bill = PhoneBill::findOne(['number' => $str_cell, 'date' => $str_date]);
                    if ($phone_bill) {
                        $phone_bill->subscription = $subscription;
                        $phone_bill->one_time = $one_time;
                        $phone_bill->online = $online;
                        $phone_bill->roaming = $roaming;
                        $phone_bill->cost = $cost;
                        $phone_bill->save();
                    } else {
                        $phone_bill         = new PhoneBill();
                        $phone_bill->number = $str_cell;
                        $phone_bill->date   = $str_date;
                        
                        $phone_bill->subscription = $subscription;
                        $phone_bill->one_time = $one_time;
                        $phone_bill->online = $online;
                        $phone_bill->roaming = $roaming;
                        $phone_bill->cost   = $cost;
                        $phone_bill->save();
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Загрузка данных прошла успешно');
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка чтения файла');
            return false;
        }
    }
    
    /**
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionUploadform(){
        $model = new CellNumbers();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->file){
                if ($model->file->extension == 'xml'){
                    $fileName = 'costofcell';
                    $model->file->saveAs('in/' . $fileName . '.' . $model->file->extension);
                    $this->costInFile();
                    return $this->redirect(['/phone-bill']);
                }
                else {
                    $fileName = 'cellnumbers';
                    $model->file->saveAs('in/' . $fileName . '.' . $model->file->extension);
                    $this->actionReadFile();
                    return $this->redirect(['index']);
                }
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload' => true,
                'progress' => 0
            ]);
        }
    }
    
    /**
     * @return \yii\web\Response
     */
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
     * @throws \yii\db\Exception
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
