<?php

namespace backend\controllers;

use Yii;
use kartik\mpdf\Pdf;
use backend\models\Devices;
use backend\models\DtDefsheetDevices;
use backend\models\DtDefsheetDevicesSearch;
use backend\models\DtDefsheets;
use backend\models\DtDefsheetsSearch;
use backend\models\Tasks;
use backend\models\Images;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * DtDefsheetsController implements the CRUD actions for DtDefsheets model.
 */
class DtDefsheetsController extends Controller
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
     * Lists all DtDefsheets models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DtDefsheetsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Открыть документ Акт Списания.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $ddsDeviceSearch = new DtDefsheetDevicesSearch();
        $ddsDeviceProvider = $ddsDeviceSearch->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'ddsDeviceSearch' => $ddsDeviceSearch,
            'ddsDeviceProvider' => $ddsDeviceProvider,
        ]);
    }

    /**
     * Creates a new DtDefsheets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DtDefsheets();
        $model->employee_id = 0;
        $model->employee_name = 'name';
        $model->status = 0;
        if ($model->save())
            return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Редактировать Акт Списания. Изменить Сотрудника.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {

            //$pos = strpos($model->employee_name, "]"); //Ид сотрудника получаем в виде строки [{Код}] {ФИО}
            //$id_emp = (int)substr($model->employee_name, 1, $pos-1);
            //$model->employee_id = $id_emp;
            $model->status = 0;

            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {//
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаляем документ Акт Списания и его табличную часть.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == DtDefsheets::STATUS_CONFIRM) {
            throw new ForbiddenHttpException('Нельзя удалить подтвержденный документ');
        }
        if ($model->delete())
            DtDefsheetDevices::deleteAll(['dt_defsheets_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Сохраняем документ Акт Списания. Перемещаем все выбранные устройства в "Неисправное оборудование"
     * @param $id
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionSave($id){
        $model = $this->findModel($id);
        $list_devices = DtDefsheetDevices::findAll(['dt_defsheets_id' => $id]);
        //var_dump($list_devices);
        $succ = false;

        foreach ($list_devices as $device){
            /* @var $mod_device Devices */
            $mod_device = $device->devices;
            $device->status = DtDefsheetDevices::STATUS_127;
            if ($mod_device->setTowp(127, 'Акт списания №' . $id) && $device->save()) $succ = true;
        }

        if ($succ) {
            $model->employee_name = 'name'; //костыль, ибо требует заполнить, а со сценариями не хочется заморачиваться
            $model->status = 1;
            $model->user_id = Yii::$app->user->id;
            if ($model->employee_id != 0)
                if ($model->save()) {
                    $err = Tasks::Create(Yii::$app->user->id, 'Подписать Акт на списание №' . $id, 1,
                        "Вы создали и сохранили документ " . Html::a('Акт на списание №' . $id, ['dt-defsheets/view', 'id' => $id]) . ". \r\n
                        Теперь вам необходимо распечатать Акт, подписать и загрузить скан подписанного документа."
                    );
                    Yii::$app->session->setFlash('success', 'Документ успешно сохранён!'
                        . "\r\n Распечатайте, подпишите и загрузите скан подписаного документа.");
                } else {
                    $str_err = '';
                    foreach ($model->errors as $error){
                        $str_err .= implode(' ', $error) . '<br>';
                    }
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении документа: <br>' . $str_err);
                }
            else
                Yii::$app->session->setFlash('error', 'Необходимо указать сотрудника');
        }
        else Yii::$app->session->setFlash('error', 'Необходимо добавить хотябы одно устройство ' . serialize($device->errors));
        return $this->redirect(['dt-defsheets/view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreatePdf($id){
        $model = $this->findModel($id);
        $query = DtDefsheetDevices::find()->where(['dt_defsheets_id' => $id]);
        $dddProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->layout = 'pdf';
        /** @var Pdf $pdf */
        $pdf = Yii::$app->pdf;
        $pdf->options = ['title' => 'Акт списания №' . $model->id];
        $pdf->filename = 'ActDefsheets_'. $model->id .'_'. $model->date_create .'.pdf';
        $pdf->content = $this->render('pdf', [
            'model' => $model,
            'dddProvider' => $dddProvider
        ]);
        return $pdf->render();
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAgree($id){
        $model = $this->findModel($id);
        $key = md5('dt-defsheets' . $id);
        if (Images::getLinkfile($key)){
            $model->employee_name = 'name';
            $model->status = DtDefsheets::STATUS_CONFIRM;
            $model->date_confirm = date('Y-m-d');
            if ($model->save())
                Yii::$app->session->setFlash('success', 'Документ подтвержден!');
            else
                Yii::$app->session->setFlash('error', serialize($model->errors));
        } else {
            Yii::$app->session->setFlash('error', 'Необходимо загрузить скан документа перед подтверждением');
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the DtDefsheets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DtDefsheets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DtDefsheets::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
