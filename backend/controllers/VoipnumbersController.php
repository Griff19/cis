<?php
/**
 * Контроллер для модели VoipNumbers "Внутренние номера".
 */
namespace backend\controllers;

use Yii;
use backend\models\VoipNumbers;
use backend\models\VoipnumbersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class VoipnumbersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index','view','create', 'choicewp', 'choicenull', 'update'],
                        'allow' => true,
                        'roles' => ['sysadmin'],
                    ],
                    [
                        'actions' => ['delete'],
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
     * Lists all VoipNumbers models.
     * @return mixed
     */
    public function actionIndex($id_wp = null)
    {
        $searchModel = new VoipnumbersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_wp' => $id_wp
        ]);
    }

    /**
     * Displays a single VoipNumbers model.
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
     * Создаем новый внутренний номер
     * @return mixed
     */
    public function actionCreate($id_dev = 0)
    {
        $model = new VoipNumbers();
        $model->device_id = $id_dev;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($id_dev == 0) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['devices/view', 'id' => $id_dev]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VoipNumbers model.
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
     * Deletes an existing VoipNumbers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param int $id_dev
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $id_dev = 0)
    {
        $this->findModel($id)->delete();
        if ($id_dev == 0) {
            return $this->redirect(['index']);
        } else {
            return $this->redirect(['devices/view', 'id' => $id_dev]);
        }
    }

    /**
     * @param $id
     * @param $id_dev
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionChoicedevice($id, $id_dev){
        $model = $this->findModel($id);
        $arr = VoipNumbers::find()->select("MAX(status) as status")->where(['device_id' => $id_dev])->all();
        if ($arr[0]['status']) $stat = $arr[0]['status'] + 1;
        else $stat = 1;
        $model->device_id = $id_dev;
        $model->status = $stat;
        if ($model->save())
            return $this->redirect(['devices/view', 'id' => $id_dev]);
    }

    /**
     * @param $id
     * @param $id_dev
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionChoicenull($id, $id_dev = 0, $id_wp = 0) {
        $model = $this->findModel($id);
        //$arr = VoipNumbers::find()->select("MAX(status) as status")->where(['device_id' => null])->all();
        //if ($arr[0]['status']) $stat = $arr[0]['status'] + 1;
        //else $stat = 1;
        if ($id_wp == 0) {
            $model->device_id = null;
            $model->status = 1;
            if ($model->save()) return $this->redirect(['devices/view', 'id' => $id_dev]);
        }
        else {
            $model->workplace_id = null;
            $model->status = 1;
            if ($model->save()) return $this->redirect(['workplaces/view', 'id' => $id_wp]);
        }
        Yii::$app->session->setFlash('error', 'Ошибка.');
        return $this->redirect('');
        //$model->status = $stat;

    }

    /**
     * @param $id
     * @param $id_wp
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionChoicewp($id, $id_wp) {
        $model = $this->findModel($id);
        $arr = VoipNumbers::find()->select("MAX(status) as status")->where(['workplace_id' => $id_wp])->all();
        if ($arr[0]['status']) $stat = $arr[0]['status'] + 1;
        else $stat = 1;
        $model->workplace_id = $id_wp;
        $model->status = $stat;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Номер установлен на рабочее место');
            return $this->redirect(['workplaces/view', 'id' => $id_wp]);
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось сохранить');
            return $this->redirect('');
        }
    }


    /**
     * Загружаем данные по сотрудникам из файла.
     * Поле snp используется для заполнения полей name, surname и patronymic
     * @return \yii\web\Response
     */
    public function actionReadfile(){
        $filename = 'in/voipnumbers.txt';
        $readfile = fopen($filename, 'r');
        $i = 0; $err = '';
        while ($str = fgets($readfile, 1024)){

            $items = explode(";", $str);
            //0     ;1    ;2  ;3     ;4          ;5      ;6           ;7        ;8
            //Филиал;Номер;Исп;Secret;Description;Context;MAC телефона;Должность;ФИО
            if (count($items) != 9) {Yii::$app->session->setFlash('error', 'Файл не соответствует формату!'); break;}
            if ($items[1] == 'Номер') continue;
            //var_dump($items);
            //continue;
            $voip = VoipNumbers::findOne(['voip_number' => (int)$items[1]]);

            if(isset($voip)) {
                // если в базе уже есть этот номер то пропускаем, иначе создаем новый
            } else {
                $voip = new VoipNumbers();
                $voip->voip_number = (int)$items[1];
                $voip->secret = $items[3];
                $voip->description = $items[4];
                $voip->context = $items[5];
                if ($voip->validate()) {
                    $i++;
                    $voip->save();
                }
                else {
                    foreach ($voip->errors as $key => $error){
                        $err .= '<b>' . $key . '</b> ' . implode('   ', $error) . '<br>';
                    }
                }

            }
        }
        fclose($readfile);
        if ($i > 0) Yii::$app->session->setFlash('success', 'Обработано ' . $i . ' номеров.');
        if (!empty($err)) Yii::$app->session->setFlash('error', $err);

        return $this->redirect(['index']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionUploadform(){
        $model = new VoipNumbers();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->file){
                $fileName = 'voipnumbers';
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
     * Finds the VoipNumbers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoipNumbers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoipNumbers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
