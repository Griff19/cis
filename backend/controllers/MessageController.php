<?php

namespace backend\controllers;

use Yii;
use backend\models\Message;
use backend\models\MessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class MessageController extends Controller
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
     * @param null $mode
     * @return string
     */
    public function actionIndex($mode = null)
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $mode);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();
        $model->from_user_id = Yii::$app->user->id;
        $model->type = Message::TYPE_MESSAGE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
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
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Меняем статус сообщния на "Прочитано"
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRead($id){
        $model = $this->findModel($id);

        $model->status = Message::STATUS_READ;
        if ($model->save())
            Yii::$app->session->setFlash('success', 'Сообщение прочитано.');
        return $this->redirect(['message/view', 'id' => $id]);
    }

    /**
     * Меняем статус на "не прочитанно"
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionNotRead($id){
        $model = $this->findModel($id);

        $model->status = Message::STATUS_CREATED;
        if ($model->save())
            Yii::$app->session->setFlash('error', 'Сообщение не прочитано.');
        return $this->redirect(['message/view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
