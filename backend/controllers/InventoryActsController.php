<?php
/**
 * Контроллер документа "Акт инвентаризации".
 */

namespace backend\controllers;

use Yii;
use backend\models\Devices;
use backend\models\Reports;
use backend\models\StoryDevice;
use backend\models\Message;
use backend\models\User;
use backend\models\Workplaces;
use backend\models\InventoryActs;
use backend\models\InventoryActsTb;
use backend\models\InventoryActsSearch;
use backend\models\InventoryActsTbSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;

class InventoryActsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'delete', 'save', 'create-tb', 'create-pdf', 'agree'],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['update', 'readfile', 'uploadform'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список актов инвентаризации.
     * @param null $id_wp
     * @return mixed
     */
    public function actionIndex($id_wp = null)
    {
        $searchModel = new InventoryActsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_wp' => $id_wp
        ]);
    }

    /**
     * Отображаем Акт инвентаризации по рабочему месту
     * со списком устройств с которыми можно работать
     * @param integer $id идентификатор/номер документа Акт Инвентаризации
     * @param null $id_wp
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $id_wp = null)
    {
        $model = $this->findModel($id);

        $iatSearch = new InventoryActsTbSearch();
        $iatProvider = $iatSearch->search(Yii::$app->request->queryParams);
        if ($model->status == InventoryActs::DOC_NEW)
            $devProvider = Reports::getInventoryData($model->workplace_id);
        else
            $devProvider = $iatSearch->searchAll(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $model,
            'iatSearch' => $iatSearch,
            'iatProvider' => $iatProvider,
            'devProvider' => $devProvider,
            'id_wp' => $id_wp
        ]);
    }

    /**
     * Создаем Акт инвентаризации.
     * @param integer $id_wp идентификатор рабочего места
     * @return mixed
     */
    public function actionCreate($id_wp = null)
    {
        $count = InventoryActs::find()->where(['workplace_id' => $id_wp])
            ->andWhere(['<=', 'status', 2])->count();
        if ($count > 0) {
            Yii::$app->session->setFlash('error', 'Имеется не завершенный документ. Завершите или удалите его.');
            return $this->redirect(['index', 'id_wp' => $id_wp]);
        }
        $model = new InventoryActs();
        if ($id_wp) {
            $model->status = 0;
            $model->workplace_id = $id_wp;
            $workplace = Workplaces::findOne($id_wp);
            if ($workplace->owner) {
                $model->owner_employee_id = $workplace->owner[0]->id;
                $model->owner_name = $workplace->owner[0]->snp;
            }
        }
        /* @var $user_exec User */
        $user_exec = User::findOne(Yii::$app->user->id);

        $model->employee_name = $user_exec->employee->snp;
        $model->exec_employee_id = $user_exec->employee->id;
        $model->act_date = Yii::$app->formatter->asDate(new \DateTime(), 'yyyy-MM-dd');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id, 'id_wp' => $id_wp]);
            else {
                Yii::$app->session->setFlash('error', Json::encode($model->errors));
                return $this->redirect(['index', 'id_wp' => $id_wp]);
            }
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Экшн позволяет добавить в табличную часть документа Акт инвентаризаци
     * новую строку и вернутся обратно в inventory-acts/view
     * @param $act_id
     * @param $dev_id
     * @param null $id_wp
     * @param null $aux
     * @param $status
     * @return string
     */
    public function actionCreateTb($act_id, $dev_id, $id_wp = null, $aux = null, $status)
    {
        if (InventoryActsTb::CreateTb($act_id, $dev_id, $id_wp, $aux, $status)) {
        } else Yii::$app->session->setFlash('error', 'Ошибка обработки строки таблицы!');

        $model = InventoryActs::findOne($act_id);
        $iatSearch = new InventoryActsTbSearch();
        $iatProvider = $iatSearch->search(Yii::$app->request->queryParams);
        $devProvider = Reports::getInventoryData($model->workplace_id);
        return $this->render('view', [
            'model' => $model,
            'iatSearch' => $iatSearch,
            'iatProvider' => $iatProvider,
            'devProvider' => $devProvider
        ]);
    }

    /**
     * Updates an existing InventoryActs model.
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
     * Удаляем документ и его табличную часть
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        parse_str(Yii::$app->request->queryString, $arr);

        $model = $this->findModel($id);
        if (InventoryActsTb::deleteAll(['act_id' => $id])) {
        } else
            Yii::$app->session->setFlash('error', 'Возникла ошибка при удалении строк документа');

        if ($model->delete()) {
        } else
            Yii::$app->session->setFlash('error', 'Возникла ошибка при удалении документа');

        $id_wp = ArrayHelper::getValue($arr, 'id_wp');
        if ($id_wp)
            return $this->redirect(['index', 'id_wp' => $id_wp]);
        else
            return $this->redirect(['index']);
    }

    /**
     * Отражаем устройства на рабочем месте
     * @param $id_wp
     * @return string
     */
    public function actionDevices($id_wp)
    {
        $dataProvider = Reports::getInventoryData($id_wp);
        return $this->render('devices', [
            'dataProvider' => $dataProvider,
            'id_wp' => $id_wp
        ]);
    }

    /**
     * Сохраняем акт инвентаризации
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSave($id)
    {
        /* @var $modelTb InventoryActsTb */
        /* @var $device Devices */
        /* @var $new_dev Devices */
        //Yii::$app->session->setFlash('info', 'Запуск сохранения');
        $err = '';
        $suc = '';

        $modelAct = $this->findModel($id); //текущий документ инвентаризации
        $id_wp = $modelAct->workplace_id;

        $devices = Devices::getIdsOnwp($id_wp)->models;

        //var_dump($modelsTb->models); die;
        foreach ($devices as $device_arr) {
            //текущее устройство на рабочем месте
            $device = Devices::findOne($device_arr['id']);
            //строка в акте инвентаризации
            $modelTb = InventoryActsTb::findOne(['act_id' => $modelAct->id, 'device_id' => $device->id]);

            if ($modelTb) {
                if ($modelTb->status == InventoryActs::MISSING_DEV) {
                    if ($device->setTowp(130))
                        $suc .= 'Успех! Устройство перемещено в пропавшие ' . $device_arr['id'] . '<br>';
                    else
                        $err .= 'Ошибка! Ошибка перемещения устройства ' . $modelTb->device_id . ' на РМ №130<br>';
                } elseif ($modelTb->status == InventoryActs::REPLACE_DEV) {
                    $new_dev = Devices::findOne($modelTb->aux);
                    $new_dev->workplace_id = $id_wp; //перемещаем на рабочее место
                    $new_dev->parent_device_id = $device->parent_device_id; //устанавливаем в родителя

                    $device->workplace_id = 131; //перемещаем в пропавшие
                    $device->parent_device_id = null; //снимаем с родителя

                    if ($device->save()) {
                        $suc .= 'Успех! Устройство заменено ' . $device_arr['id'] . '<br>';
                        if ($new_dev->save())
                            $suc .= 'Успех! Устройство ' . $new_dev->id . ' перемещено на РМ №' . $modelAct->workplace_id . '<br>';
                        else
                            $err .= 'Ошибка! Ошибка перемещения ' . $new_dev->id . ' на РМ №' . $modelAct->workplace_id . '<br>';
                    } else
                        $err .= 'Ошибка! Ошибка замены ' . $device->id . '<br>';
                } elseif ($modelTb->status == InventoryActs::ADDITION_DEV) {//добавленное устройство
                    $device->fake_device = 0;

                    if ($device->save())
                        $suc .= 'Успех! Устройство ' . $device_arr['id'] . ' добавлено на РМ №' . $id_wp . '<br>';
                    else
                        $err .= 'Ошибка! Ошибка добавления нового устройства ' . $device->id . '<br>';
                } elseif ($modelTb->status == InventoryActs::DEVICE_OK) {
                }
            } else {
                $modelTb = new InventoryActsTb();
                $modelTb->act_id = $modelAct->id;
                $modelTb->device_id = $device_arr['id'];
                $modelTb->device_workplace_id = $id_wp;
                //$model->aux = $aux;
                $modelTb->status = InventoryActs::DEVICE_OK;
                if ($modelTb->save())
                    $suc .= 'Успех! Проверено устройство ' . $device_arr['id'] . '<br>';
                else
                    $err .= 'Ошибка! Не удалось добавить устройство в акт ' . $device_arr['id'] . '<br>';
            }
        }
        $modelsTb = InventoryActsTb::find()->where(['act_id' => $id])->all();
        foreach ($modelsTb as $model) {
            /* @var $model InventoryActsTb */
            if (!in_array($model->device_id, $devices)) {
                if ($model->status == InventoryActs::REPLACE_DEV) {
                    $device = Devices::findOne($model->device_id);
                    if ($device->setTowp($id_wp))
                        $suc .= 'Успех! Устройство ' . $device->id . ' перемещено.<br>';
                    else
                        $err .= 'Ошибка! Устройство ' . $device->id . ' не перемещено.<br>';
                }
            }
        }

        Yii::$app->session->setFlash('info', $err . '<br>' . $suc);

        $modelAct->status = InventoryActs::DOC_SAVED;
        $modelAct->save();
        //die;
        $usr = User::findOne(['employee_id' => $modelAct->exec_employee_id]);
        Message::Create($usr->id, 'Подписать Акт инвентаризации №' . $modelAct->id, 1,
            "Вы создали и сохранили документ " . Html::a('Акт инвентаризации №' . $modelAct->id, ['inventory-acts/view', 'id' => $id]) . ". \r\n
            Теперь вам необходимо распечатать Акт, подписать и загрузить скан подписанного документа."
        );

        return $this->redirect(['inventory-acts/view', 'id' => $id, 'id_wp' => $id_wp]);
    }

    /**
     * Создаем документ pdf
     * @param integer $id Идентификатор документа "Акт инвентаризации"
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreatePdf($id)
    {
        $model = $this->findModel($id); //текущий акт инвентаризации
        $modelStatusArr = $model->arrayDevIDinTb(); //Получаем массив статусов текущего акта

        $iatSearch = new InventoryActsTbSearch(); //поиск табличной части акта инвентаризации
        $iatProvider = $iatSearch->search(Yii::$app->request->queryParams, InventoryActs::MISSING_DEV);

        $oldModel = (new InventoryActs())->getLastAct($model->workplace_id, $id);
        if ($oldModel)
            $oldModelArray = $oldModel->arrayDevIDinTb(); //получаем  массив статусов из старого акта, ид в ключах массива
        $arr_dev = [];

        // если документ уже сохранен то берем информацию из сохраненных данных
        // если новый то - данные берутся на текущий момент
        if ($model->status == InventoryActs::DOC_NEW)
            $provider_devices = Reports::getInventoryData($model->workplace_id)->models;
        else
            $provider_devices = InventoryActsTbSearch::searchAll($id)->models;

        foreach ($provider_devices as $model_dev) {
            if ($modelStatusArr[$model_dev['id']] == InventoryActs::MISSING_DEV) continue;
            $arr_dev[] = Devices::findOne($model_dev['id']);
        }

        $devProvider = new ArrayDataProvider([
            'allModels' => $arr_dev, //текущий список устройств на рабочем месте
            'pagination' => false,
        ]);

        //получаем по ид модели устройств в старом акте инвентаризации
        $arr = []; //массив для хранения моделей устройств последней инвентаризации
        $newDev = []; //массив для хранения моделей добавленных устройств
        $lostDev = []; //массив для хранения потеряных устройств
        $arrCurrDev = $devProvider->models; //модели текущих устойств на рабочем месте
        $arrCons = []; //массив для хранения перемещенных с рабочего места устройств
        if ($oldModel) {
            //Получаем новые устройства на рабочем месте после последней инвентаризации
            foreach ($arrCurrDev as $currdev) {
                //var_dump(array_key_exists($currdev['id'], $oldModelArray));
                if (!array_key_exists($currdev['id'], $oldModelArray)) {
                    $newDev[] = Devices::findOne($currdev['id']);
                }
            }
            $newDevProvider = new ArrayDataProvider([
                'allModels' => $newDev,
                'pagination' => false
            ]);
            //Получаем устройства по старой инвентаризации (старая инвентаризация полностью)
            foreach ($oldModelArray as $id => $status) {
                $devModel = Devices::findOne($id);
                $arr[] = $devModel;
                $story_dev = StoryDevice::getStoryWp($model->workplace_id, $id, $oldModel->act_date);
                if ($story_dev)
                    if (ArrayHelper::getValue($modelStatusArr, $id) != InventoryActs::MISSING_DEV &&
                        ArrayHelper::getValue($modelStatusArr, $id) != InventoryActs::DEVICE_OK
                    )
                        $arrCons[] = Devices::findOne($story_dev[0]['id_device']);
            }

            $oldModelProvider = new ArrayDataProvider([
                'allModels' => $arr,
                'pagination' => false,
            ]);

            $consModelProvider = new ArrayDataProvider([
                'allModels' => $arrCons,
                'pagination' => false,
            ]);
        }
        //Получаем потеряные утройства, проверяя статус строк в документе
        foreach ($iatProvider->models as $strTable) {
            //var_dump($strTable);
            if ($strTable->status == InventoryActs::MISSING_DEV) {
                $lostDev[] = Devices::findOne($strTable->device_id);
            }
        }
        $lostDevProvider = new ArrayDataProvider([
            'allModels' => $lostDev,
            'pagination' => false
        ]);

        $this->layout = 'pdf';
        /** @var Pdf $pdf */
        $pdf = Yii::$app->pdf;
        $pdf->options = ['title' => 'Акт инвентаризации №' . $model->id];
        //$pdf->filename = 'InventoryAct_'. $model->id .'_'. $model->act_date .'.pdf';
        //$pdf->content = "Содержимое";
        $pdf->content = $this->render('pdf', [
            'model' => $model,
            'iatProvider' => $iatProvider,
            'devProvider' => $devProvider,
            'oldModel' => $oldModel ? $oldModel : null,
            'oldModelTable' => $oldModel ? $oldModelProvider : null,
            'newDevProvider' => $oldModel ? $newDevProvider : null,
            'consModelProvider' => $oldModel ? $consModelProvider : null,
            'lostDevProvider' => $oldModel ? $lostDevProvider : null
        ]);

        $model->status = InventoryActs::DOC_PRINTED;
        $model->save();

        return $pdf->render();
    } //actionCreatePdf()

    /**
     * Подтверждение документа
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAgree($id)
    {
        $model = $this->findModel($id);
        if ($model->status == InventoryActs::DOC_PRINTED) {
            $model->status = InventoryActs::DOC_AGREE;
            if ($model->save())
                Yii::$app->session->setFlash('success', 'Документ подтвержден!');
        } else {
            Yii::$app->session->setFlash('error', 'Документ должен быть распечатан!');
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the InventoryActs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventoryActs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventoryActs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
