<?php

namespace backend\controllers;

use Yii;
use backend\models\Images;
use backend\models\ImagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ImagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список изображений
     * если $owner установлен то выводится список с возможностью выбора изображения
     * @return mixed
     */
    public function actionIndex($owner = null, $owner_id = null)
    {
        $searchModel = new ImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'owner' => $owner,
            'owner_id' => $owner_id
        ]);
    }

    /**
     * Displays a single Images model.
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
     * Загружаем изображение
     * @param null $owner владелец изображения это имя класса и идентификатора md5('workplace' . $model->id)
     * @param null $owner_id идентификатор владельца куда нужно вернуться после выполнения
     * @param null $target контроллер владельца
     * @return mixed
     */
    public function actionCreate($owner = null, $owner_id = null, $target = null)
    {
        $model = new Images();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->file){
                $fileName = $model->file->baseName;
                $fileName = md5($fileName . time());
                if($model->file->saveAs('images/'. $fileName .'.'. $model->file->extension)) {
                    $model->linkfile = 'images/'. $fileName .'.'. $model->file->extension;

                    $old_models = Images::findAll(['owner' => $owner]);
                    /* @var $old_model Images */
                    foreach ($old_models as $old_model){
                        $old_model->owner = ' ';
                        $old_model->save();
                    }
                    $model->owner = $owner;
                    $model->img_group = $target;
                    if ($model->save()){
                        Yii::$app->session->setFlash('success', 'Файл успешно загружен');
                    } else
                        Yii::$app->session->setFlash('error', 'Файл не удалось загрузить. Возможно файл с таким именем уже существует.');
                }
            }
            if ($owner_id)
                return $this->redirect([$target, 'id' => $owner_id]);
            elseif ($target)
                return $this->redirect([$target]);
            else
                return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload' => true,
            ]);
        }
    }

    /**
     * Updates an existing Images model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_path = $model->linkfile;
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file){
                $fileName = $model->file->baseName;
                $fileName = md5($fileName);
                if($model->file->saveAs('images/'. $fileName .'.'. $model->file->extension)) {
                    $model->linkfile = 'images/'. $fileName .'.'. $model->file->extension;
                    if ($model->save()) {
                        unlink($old_path);
                        Yii::$app->session->setFlash('success', 'Изображение успешно обновлено.');
                    } else
                        unlink('images/'. $fileName .'.'. $model->file->extension);
                        Yii::$app->session->setFlash('error', 'Обновление не удалось. Возможно файл с таким именем уже существует.');
                }
            } else Yii::$app->session->setFlash('error', 'Файл не удалось загрузить.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Устанавливаем владельца на изображение
     * @param $id
     * @param $param
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSetowner($id, $param){
        parse_str($param, $arr);
        $owner = $arr['owner'];
        $owner_id = $arr['owner_id'];
        $target = $arr['target'];

        $old_models = Images::findAll(['owner' => $owner]);
        /* @var $old_model Images */
        foreach ($old_models as $old_model){
            $old_model->owner = ' ';
            $old_model->save();
        }

        $model = $this->findModel($id);
        $model->owner = $owner;
        if ($model->save()) Yii::$app->session->setFlash('success', 'Изображение установлено');
        else Yii::$app->session->setFlash('error', 'Не удалось установить изображение');
        return $this->redirect([$target, 'id' => $owner_id]);
    }

    /**
     * Deletes an existing Images model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $path = $model->linkfile;
        if (file_exists($path)) {
            unlink($path);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
