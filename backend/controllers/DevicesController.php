<?php

namespace backend\controllers;

use backend\models\DeviceType;
use backend\models\DtEnquiryDevices;
use backend\models\DtInvoiceDevices;
use Yii;
use backend\models\InventoryActs;
use backend\models\InventoryActsTb;
use backend\models\StoryDevice;
use backend\models\Devices;
use backend\models\DevicesSearch;
use backend\models\VoipnumbersSearch;
use backend\models\Netints;
use backend\models\NetintsSearch;
use backend\models\Parameters;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;


/**
 * DevicesController implements the CRUD actions for Devices model.
 * @property array|\yii\db\ActiveRecord[] curr_type
 */
class DevicesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'index-to-enquiry',
                            'index-comp',
                            'view',
                            'view-table-comp',
                            'update',
                            'create',
                            'create-from-doc',
                            'validation',
                            'addcomp',
                            'change-by-attr',
                            'get-brands',
                            'get-models',
                            'get-specifications',
                            'set-specification-auto',
                            'set-type-id',
                            'delfromwp',
                            'delcomp',
                            'find-device'
                        ],
                        'allow' => true,
                        'roles' => ['it'],
                    ],
                    [
                        'actions' => ['delete', 'addtowp'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== 'autocreate');
        return parent::beforeAction($action);
    }

    /**
     * Список устройств.
     * $mode = 'def' - стандартный вид
     * $mode = 'wps' - выбираем устройство для рабочего места
     * $mode = 'dvs' - выбираем устройство как комплектующее
     * @param string $mode
     * @param null $target
     * @param null $id_dev
     * @param null $id_wp
     * @return mixed
     */
    public function actionIndex($mode = 'def', $target = null, $id_dev = null, $id_wp = null)
    {
        $searchModel = new DevicesSearch();
        if ($mode == 'wps') $id_wp = null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id_wp);
        Yii::$app->session->set('mode', $mode);
        if (Yii::$app->request->isAjax) {
            //
            return $this->renderAjax('to_enquire', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'mode' => $mode,
                'target' => $target,
                'id_dev' => $id_dev,
                'id_wp' => $id_wp,
            ]);
        } else {
            //обычный режим
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'mode' => $mode,
                'target' => $target,
                'id_dev' => $id_dev,
                'id_wp' => $id_wp,
            ]);
        }
    }

    /**
     * @param null $target
     * @return string
     */
    public function actionIndexComp($target = null)
    {
        $searchModel = new DevicesSearch();
        $dataProvider = $searchModel->searchComp(Yii::$app->request->queryParams);
        return $this->render('index_comp', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'target' => $target
        ]);
    }

    /**
     * Экшн используется для развертывания списка комплектующих устройств
     * в таблице устройств на странице рабочего места
     * @param $id
     * @return string
     */
    public function actionViewTableComp($id)
    {
        $searchModel = new DevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 0, $id);
        return $this->renderAjax('view_table_comp', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Функция вызывает таблицу устройств, находящихся на складе
     * для их резервирования за рабочим местом, указанным в документе "Заявка"
     * @param null $target
     * @param null $id_dev
     * @return string
     */
    public function actionIndexToEnquiry($target = null, $id_dev = null)
    {
        $searchModel = new DevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1);

        //обычный режим
        return $this->render('indexStock', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'target' => $target,
            'id_dev' => $id_dev,

        ]);

    }

    /**
     * Выводим подробную информацию об устройстве вместе с данными из таблицы parameters
     * @param integer $id
     * @param int $id_wp Идентификатор рабочего места
     * @return mixed
     */
    public function actionView($id, $id_wp = 0)
    {
        $param_model = Parameters::findOne(['id_device' => $id]);

        $netSearch = new NetintsSearch();
        $netProvider = $netSearch->search(Yii::$app->request->queryParams, $id);

        $voipSearch = new VoipnumbersSearch();
        $voipProvider = $voipSearch->search(Yii::$app->request->queryParams, $id);

        $compSearch = new DevicesSearch();
        $compProvider = $compSearch->search(Yii::$app->request->queryParams, 0, $id);

        Yii::$app->session->set('id_wp', $id_wp);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'param_model' => $param_model,
            'voipSearch' => $voipSearch,
            'voipProvider' => $voipProvider,
            'netSearch' => $netSearch,
            'netProvider' => $netProvider,
            'compSearch' => $compSearch,
            'compProvider' => $compProvider,

        ]);
    }

    /**
     * Валидация формы ввода
     * @param $scenario
     * @return array
     */
    public function actionValidation($scenario)
    {
        $model = new Devices(['scenario' => $scenario]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if ($model->brand) Yii::$app->session->set('brand', $model->brand);
            else Yii::$app->session->remove('brand');

            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }
    }

    /**
     * Создаем устройство. При создании получаем строку параметров.
     * @param $param
     * @return mixed
     */
    public function actionCreate($param = null)
    {
        /* @var $model Devices */
        parse_str(Yii::$app->request->queryString, $arr);
        $target = ArrayHelper::getValue($arr, 'target');
        $target_id = ArrayHelper::getValue($arr, 'target_id');
        $id_wp = ArrayHelper::getValue($arr, 'id_wp');
        $id_dev = ArrayHelper::getValue($arr, 'id_dev');

        $query = Yii::$app->request->queryString;
        $model = new Devices(['scenario' => Devices::SCENARIO_INSERT]);
        //var_dump($model->scenarios()); die;
        $model->workplace_id = $id_wp;

        if ($model->load(Yii::$app->request->post())) {
            $mode = '';

            if ($id_dev > 0) { //если устройство создается при выборе комплектующего то сразу добавляем ему родителя
                $model->parent_device_id = $id_dev;
            }

            //Помечаем устройсво как фейковое (при создании из акта инвентьаризации)
            if ($target == 'new-dev') $model->fake_device = 1;
            if ($model->validate()) {
                if ($model->save()) {
                    if (!empty($model->device_mac)) {
                        $net = new Netints();
                        $net->device_id = $model->id;
                        $net->mac = $model->device_mac;
                        $net->save();
                    }
                    StoryDevice::addStory($id_wp, $model->id, StoryDevice::EVENT_CREATE, '' . $target . ' ' . $target_id);
                }
                if ($model->chekMode) {
                    $model->sn = 'SN' . $model->id;
                    $model->save();
                }
                Yii::$app->session->setFlash('success', '<b>' . $model->summary . '</b> устройство успешно добавлено');
            } else {
                Yii::$app->session->setFlash('error', serialize($model->errors));
            }

            //готовим возврат во view
            if ($target == 'new-dev') {
                $act_id = ArrayHelper::getValue($arr, 'act_id');
                $id_wp = ArrayHelper::getValue($arr, 'id_wp');
                $status = InventoryActs::ADDITION_DEV;
                InventoryActsTb::CreateTb($act_id, $model->id, $id_wp, null, $status);
                //$query = 'act_id='. $act_id .'&id_wp='. $id_wp. '&dev_id='. $model->id . '&status=' . $status;
                $target = 'inventory-acts/view';
                $query = 'id=' . $act_id;
            }
            if ($target == 'devices/addtowp') {
                $target = 'workplaces/view';
                $query = 'id=' . $id_wp;
            }
            if ($target == 'devices/addcomp') {
                $target = 'devices/view';
                $query = 'id=' . $target_id;
            }

            if ($target) {
                return $this->redirect('/admin/' . $target . '?' . $query);
            } elseif ($id_dev > 0) { //при выборе комплектующего отправляем пользователя на страницу устройства
                return $this->redirect(['devices/view', 'id' => $id_dev]);
            } elseif ($id_wp > 0) {
                return $this->redirect(['workplaces/view', 'id' => $id_wp]);
            } else {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'id_wp' => $id_wp,
                'id_dev' => $id_dev,
                'mode' => 'create',
                'dt_mac' => true,
                'dt_imei' => true
            ]);
        }
    }

    /**
     * Создание устройства из введенных документов
     * @param $type_id integer тип устройства
     * @param $id_wp integer идентификатор рабочего места
     * @param $idid integer идентификатор
     * @return string|\yii\web\Response
     */
    public function actionCreateFromDoc($type_id, $idid, $id_wp = null)
    {
        $model = new Devices(['scenario' => Devices::SCENARIO_INSERT]);
        $model->type_id = $type_id;
        $model->workplace_id = $id_wp;

        //Значение $type_id сохраняем в сессию чтобы форма подстроила зависимые поля
        Yii::$app->session->set('type_id', $type_id);
        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                if (!$model->sn) {
                    $model->sn = 'SN' . $model->id;
                    $model->save();
                }
                /** @var DtInvoiceDevices $did */
                $did = DtInvoiceDevices::findOne($idid);
                $did->status = DtEnquiryDevices::DEBIT;
                $did->note = Html::a('Устройство #' . $model->id, ['devices/view', 'id' => $model->id]);
                $did->save();
                /** @var DtEnquiryDevices $ded */
                $ded = DtEnquiryDevices::findOne($did->dt_enquiry_devices_id);
                if ($ded) {
                    $ded->status = DtEnquiryDevices::DEBIT;
                    $ded->save();
                }
                Yii::$app->session->setFlash('success', '<b>'. $model->summary .'</b> устройство успешно добавлено');
                return $this->redirect(['site/employee-it']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'id_wp' => $id_wp,
                'id_dev' => null,
                'mode' => 'create',
                'dt_mac' => $model->deviceType->mac,
                'dt_imei' => $model->deviceType->imei
            ]);
        }
    }

    /**
     * Выбираем устройство по введенным данным sn, imei или mac
     * вызывается по кнопке, генерируемой скриптом js\valid_device.js
     * @param $label
     * @param $value
     * @return \yii\web\Response
     */
    public function actionChangeByAttr($label, $value)
    {
        /* @var $new_model Devices */
        if ($label != 'device_mac') {
            $new_model = Devices::findOne([$label => $value]);
        } elseif ($label == 'device_mac') {
            $new_model = Devices::findByMac($value);
        }

        parse_str(Yii::$app->request->queryString, $arr);

        $target = ArrayHelper::getValue($arr, 'target');

        if ($target == 'new-dev') {
            $act_id = ArrayHelper::getValue($arr, 'act_id');
            InventoryActsTb::CreateTb($act_id, $new_model->id, $new_model->workplace_id, null, InventoryActs::REPLACE_DEV);
        }

        $str = mb_strtoupper($label) . ' = ' . $value;
        Yii::$app->session->setFlash('success', 'Выбрано устройство по введенным данным: ' . $str);
        if ($target == 'new-dev') {
            return $this->redirect(['inventory-acts/view', 'id' => $act_id]);
        } else {
            return $this->redirect(['devices/view', 'id' => $new_model->id]);
        }
    }

    /**
     * Автоматически создаем и добавляем к рабочему месту системник, монитор, телефон и ИБП
     * нажатием одной кнопки
     * @param $id_wp
     * @return \yii\web\Response
     */
    public function actionAutocreate($id_wp)
    {
        $param = Yii::$app->request->post();

        if (array_key_exists('sys', $param)) {
            $model = new Devices();
            $model->workplace_id = $id_wp;
            $model->type_id = 1; //использовать константы в дальнейшем
            $model->device_note = '(auto)';
            if ($model->save()) {
                StoryDevice::addStory($id_wp, $model->id, 1);
                //addStory($id_wp, $model->id, 1);
            }
        }
        if (array_key_exists('mon', $param)) {
            $model = new Devices();
            $model->workplace_id = $id_wp;
            $model->type_id = 2;
            $model->device_note = '(auto)';
            if ($model->save()) {
                StoryDevice::addStory($id_wp, $model->id, 1);
                //addStory($id_wp, $model->id, 1);
            }
        }
        if (array_key_exists('tel', $param)) {
            $model = new Devices();
            $model->workplace_id = $id_wp;
            $model->type_id = 3;
            $model->device_note = '(auto)';
            if ($model->save()) {
                StoryDevice::addStory($id_wp, $model->id, 1);
                //addStory($id_wp, $model->id, 1);
            }
        }
        if (array_key_exists('ibp', $param)) {
            $model = new Devices();
            $model->workplace_id = $id_wp;
            $model->type_id = 5;
            $model->device_note = '(auto)';
            if ($model->save()) {
                StoryDevice::addStory($id_wp, $model->id, 1);
                //addStory($id_wp, $model->id, 1);
            }
        }
        return $this->redirect(['workplaces/view', 'id' => $id_wp]);
    }

    /**
     * Редактирование устройства
     * @param integer $id
     * @param null $id_wp
     * @return mixed
     * @throws NotFoundHttpException
     * @internal param null $param
     */
    public function actionUpdate($id, $id_wp = null)
    {
        //parse_str($param, $arr);
        $query = Yii::$app->request->queryParams;
        $target = ArrayHelper::getValue($query, 'target');
        //var_dump($target); die;
        if ($target)
            $target_id = ArrayHelper::getValue($query, 'target_id');

        $model = $this->findModel($id);
        Yii::$app->session->set('type_id', $model->type_id);

        if ($target == "inventory-acts-tb/create")
            $model->scenario = Devices::SCENARIO_INSERT;
        else
            $model->scenario = Devices::SCENARIO_UPDATE;

        $oldwp = $model->workplace_id;

        if ($id_wp) $model->workplace_id = $id_wp;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if ($oldwp !== $model->workplace_id) {
                    StoryDevice::addStory($oldwp, $id, StoryDevice::EVENT_OUT, 'Перемещение на РМ №' . $model->workplace_id);
                    StoryDevice::addStory($model->workplace_id, $id, StoryDevice::EVENT_IN, 'Перемещение с РМ №' . $oldwp);
                }
            }

            if ($target)
                return $this->redirect([$target, 'id' => $target_id]);
            elseif ($id_wp > 0)
                return $this->redirect(['workplaces/view', 'id' => $id_wp]);
            else
                return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'id_wp' => $id_wp,
                'mode' => 'update',
                'dt_mac' => $model->deviceType->mac,
                'dt_imei' => $model->deviceType->imei

            ]);
        }
    }

    /**
     * Удаляем устройство с рабочего места
     * @param $id int идентификатор устройства
     * @param $id_wp int идентификато рабочего места
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelfromwp($id, $id_wp)
    {
        $model = $this->findModel($id);
        if ($model->workplace_id == 1)
            $model->workplace_id = null;
        else
            $model->workplace_id = 1;
        $model->parent_device_id = 0; //если снимаем с работчего места то снимаем и с родителя
        if ($model->save()) {
            StoryDevice::addStory($id_wp, $id, StoryDevice::EVENT_OUT, 'Перенесено на РМ №' . $model->workplace_id);
            if ($model->workplace_id > 0)
                StoryDevice::addStory($model->workplace_id, $id, StoryDevice::EVENT_IN, 'Снято с РМ №' . $id_wp);
        }
        return $this->redirect(['workplaces/view', 'id' => $id_wp]);
    }

    /**
     * Добавляем устройство к рабочему месту
     * @param integer $id идентификатор устройства
     * @param null $id_wp идентификатор рабочего места
     * @param $param
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @internal param $id_wp
     */
    public function actionAddtowp($id, $id_wp = null, $param = null)
    {
        $err = false; //обнаружены ошибки в работе;
        //$target = ArrayHelper::getValue(Yii::$app->request->queryParams, 'target');
        //var_dump($target); die;
        /** @var Devices $model */
        $model = $this->findModel($id);
        $oldwp = $model->workplace_id; //старое рабочее место
        if ($oldwp == 127) {
            Yii::$app->session->setFlash('error', 'Нельзя использовать неисправное оборудование');
            $err = true;
        } elseif ($model->parent_device_id > 0) {
            Yii::$app->session->setFlash('error', 'Сначала нужно снять устройство с другого родителя');
            $err = true;
        }
        if ($param) {
            parse_str($param, $arr);
            $id_wp = (int)$arr['target_id'];
        }
        if ($id_wp)
            $model->workplace_id = $id_wp;
        else
            throw new NotFoundHttpException('Осутствуе обязательный параметр "Идентификатор рабочего места"');

        if (!$err)
            if ($model->save())
                Devices::updateAll(['workplace_id' => $id_wp], ['parent_device_id' => $model->id]);

        $query = Yii::$app->request->queryParams;
        $target = ArrayHelper::getValue($query, 'target');
        if ($target) {
            $target_id = ArrayHelper::getValue($query, 'target_id');
            StoryDevice::addStory($oldwp, $id, StoryDevice::EVENT_OUT, 'Перемещение по документу №' . $target_id . ' на РМ №' . $id_wp . ' ' . $target);
            StoryDevice::addStory($id_wp, $id, StoryDevice::EVENT_IN, 'Перемещение по документу №' . $target_id . ' c РМ №' . $oldwp . ' ' . $target);
            return $this->redirect([$target, 'id' => $target_id]);
        } else {
            StoryDevice::addStory($oldwp, $id, StoryDevice::EVENT_OUT, 'Перемещение на РМ №' . $id_wp);
            StoryDevice::addStory($id_wp, $id, StoryDevice::EVENT_IN, 'Перемещение c РМ №' . $oldwp);
            return $this->redirect(['workplaces/view', 'id' => $id_wp]);
        }
    }

    /**
     * Добавляем комплектующее к устройству
     * @param int $id идентификатор комплектующего
     * @param null $param параметры из строки УРЛ
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @internal param int $id_dev идентификатор родителя
     * @internal param int $id_wp идентификатор рабочего места кудв вернуться после исполнения
     */
    public function actionAddcomp($id, $param = null)
    {
        parse_str($param, $arr);
        $id_dev = $arr['id_dev'];
        $id_wp = $arr['id_wp'];
        $model = $this->findModel($id);
        $oldWp = $model->workplace_id;
        $model->parent_device_id = $id_dev;
        $model->workplace_id = $id_wp;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Комплектующее добавлено');
            StoryDevice::addStory($id_wp, $id, StoryDevice::EVENT_IN, 'Установка комплектующего на ' . $id_dev);
            StoryDevice::addStory($oldWp, $id, StoryDevice::EVENT_OUT, 'Установка комплектующего на ' . $id_dev . '. РМ №' . $id_wp);
        } else
            Yii::$app->session->setFlash('error', 'Ошибка добавления комплектующего');

        if ($id_dev > 0)
            return $this->redirect(['devices/view', 'id' => $id_dev]);
        else
            return $this->redirect(['workplaces/view', 'id' => $id_wp]);
    }

    /**
     * @param int $id_dev идентификатор родителя
     * @param int $id_comp идентификатор комплектующего
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelcomp($id_dev, $id_comp)
    {
        $model = $this->findModel($id_comp);
        $oldWp = $model->workplace_id;
        $model->parent_device_id = null;
        $model->workplace_id = 1; //не забываем вернуть снятое комплектующее на склад
        StoryDevice::addStory($oldWp, $id_comp, StoryDevice::EVENT_OUT, 'Снимаем комплектующее с ' . $id_dev);
        StoryDevice::addStory(1, $id_comp, StoryDevice::EVENT_IN, 'Сняли комплектующее с ' . $id_dev . ' с РМ №' . $oldWp);
        $model->save();

        return $this->redirect(['devices/view', 'id' => $id_dev]);
    }

    /**
     * Удаление устройств лучше исключить но для отладки пока нужно оставить
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            StoryDevice::delStory($id);
            Netints::deleteAll(['device_id' => $id]);
        }
        return $this->redirect(['index']);
    }

    /**
     * Автоматически добавляем комплектующие
     */
    public function actionAutocomp()
    {
        $models = Devices::findAll(['type_id' => 1]);
        $arr = [373, 363, 262, 202, 146];
        $valid = false;
        $i = 0;
        $v = 0;
        $n = 0;
        foreach ($models as $model) {
            if (in_array($model->id, $arr)) continue;
            $count_comp = Devices::find()->where(['parent_device_id' => $model->id])->count();
            //echo $model->id . ' ' . $count_comp . '<br>';
            if ($count_comp > 0) continue;
            //отсеяли лишние системники, создаем комплектующие
            $i++;
            $comp = new Devices();
            $comp->type_id = 18;
            $comp->device_note = 'Блок питания';
            $comp->workplace_id = 119;
            $comp->parent_device_id = $model->id;
            if ($comp->validate()) {
                $comp->save();
                $valid = true;
                $v++;
            }

            $comp = new Devices();
            $comp->type_id = 25;
            $comp->device_note = 'Материнская плата';
            $comp->workplace_id = 119;
            $comp->parent_device_id = $model->id;
            if ($comp->validate()) {
                $v++;
                if ($comp->save()) {
                    $net = new Netints(); //создаем сетевой интефейс со значениями по умолчанию
                    $net->device_id = $comp->id;
                    if ($net->validate()) {
                        $n++;
                        $net->save();
                    }
                }
            }

            $comp = new Devices();
            $comp->type_id = 28;
            $comp->device_note = 'Процессор';
            $comp->workplace_id = 119;
            $comp->parent_device_id = $model->id;
            if ($comp->validate()) {
                $comp->save();
                $v++;
            }

            $comp = new Devices();
            $comp->type_id = 29;
            $comp->device_note = 'DDR';
            $comp->workplace_id = 119;
            $comp->parent_device_id = $model->id;
            if ($comp->validate()) {
                $comp->save();
                $v++;
            }

            $comp = new Devices();
            $comp->type_id = 30;
            $comp->device_note = 'SSD';
            $comp->workplace_id = 119;
            $comp->parent_device_id = $model->id;
            if ($comp->validate()) {
                $comp->save();
                $v++;
            }

            $comp = null;
        }
        if ($valid) Yii::$app->session->setFlash('success', 'Обработка прошла успешно! Добавлено ' . $v . ' устройств. '
            . $n . ' сетевых интерфейсов для ' . $i . 'устройств');
        return $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public function actionFindDevice()
    {
        $deviceSearch = new DevicesSearch();
        $deviceProvider = $deviceSearch->search(Yii::$app->request->queryParams);

        return $this->render('searchDevice', [
            'deviceSearch' => $deviceSearch,
            'deviceProvider' => $deviceProvider
        ]);
    }

    /**
     * Экшн вызывается через ajax получает массив "брендов" и выводит в форме в формате Json
     * @param string $term значение вводимое в поле Бренд на форме
     * @return Json
     */
    public function actionGetBrands($term)
    {
        $type_id = Yii::$app->session->get('type_id');
        //$brands = Devices::arrayBrands($type_id, $term);
        $brands = Devices::arrBrands($type_id, $term);
        echo Json::encode($brands);

    }

    /**
     * Экшн вызывается через ajax получает массив "моделей" и выводит в форме в формате Json
     * @param string $term значение вводимое в поле Модель на форме
     * @return Json
     */
    public function actionGetModels($term)
    {
        $type_id = Yii::$app->session->get('type_id');
        $brand = Yii::$app->session->get('brand');
        $models = Devices::arrayModels($type_id, $brand, $term);
        echo Json::encode($models);
    }

    /**
     * Экшн вызывается через ajax получает массив "спецификаций" и выводит в форме в формате Json
     * @param string $term значение вводимое в поле Модель на форме
     * @return Json
     */
    public function actionGetSpecifications($term)
    {
        $type_id = Yii::$app->session->get('type_id');
        $specifications = Devices::arrSpecifications($type_id, $term);
        echo Json::encode($specifications);
    }

    /**
     * Тип устройства выбирается в форме devices/_form
     * и используется в дальнейшем для формирования автозавершения
     * @param $type_id
     * @return bool
     */
    public function actionSetTypeId($type_id)
    {
        Yii::$app->session->set('type_id', $type_id);
        $model = DeviceType::findOne($type_id);
        return Json::encode($model);
    }

    /**
     * @param string $model строка в формате encodeURIComponent()
     * @return string
     */
    public function actionSetSpecificationAuto($model)
    {
        $type_id = Yii::$app->session->get('type_id');
        $brand = Yii::$app->session->get('brand');

        $specification = Devices::arraySpecificationsAuto($type_id, $brand, $model);

        return $specification;
    }

    /**
     * Finds the Devices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Devices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
