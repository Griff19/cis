<?php

namespace backend\controllers;

use backend\models\MmAgenda;
use backend\models\MmAgendaSearch;
use backend\models\MmDecision;
use backend\models\MmDecisionSearch;
use backend\models\MmOffer;
use backend\models\MmOfferSearch;
use backend\models\MmParticipants;
use backend\models\MmParticipantsSearch;
use Yii;
use backend\models\MeetingMinutes;
use backend\models\MeetingMinutesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MeetingMinutesController implements the CRUD actions for MeetingMinutes model.
 */
class MeetingMinutesController extends Controller
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
     * Lists all MeetingMinutes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeetingMinutesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображаем Протокол встречи
     * @param integer $id Идентификатор протокола
     * @return mixed
     */
    public function actionView($id, $mod = '', $content = '')
    {
		if ($mod == 'mmagenda' && !empty($content)) {
			$mma_model = new MmAgenda();
			$mma_model->mm_id = $id;
			$mma_model->content = $content;
			$mma_model->save();
		}
		if ($mod == 'mmoffer' && !empty($content)) {
			$mmo_model = new MmOffer();
			$mmo_model->mm_id = $id;
			$mmo_model->content = $content;
			$mmo_model->save();
		}

		$mmp_model = new MmParticipants();
		$mmp_model->mm_id = $id;

		$mma_model = new MmAgenda();
		$mma_model->mm_id = $id;

		$mmo_model = new MmOffer();
		$mmo_model->mm_id = $id;

		$mmd_model = new MmDecision();
		$mmd_model->mm_id = $id;

		if ($mmp_model->load(Yii::$app->request->post()) && $mmp_model->save()) {
			$mmp_model = new MmParticipants();
			$mmp_model->mm_id = $id;
		}
		if ($mma_model->load(Yii::$app->request->post())) {
			$mma_model = new MmAgenda();
			$mma_model->mm_id = $id;
			if (!empty($mma_model->content))
				$mma_model->save();
		}
		if ($mmo_model->load(Yii::$app->request->post())) {
			$mmo_model = new MmOffer();
			$mmo_model->mm_id = $id;
			if (!empty($mmo_model->content))
				$mmo_model->save();
		}
		if ($mmd_model->load(Yii::$app->request->post())) {
			$mmd_model = new MmDecision();
			$mmd_model->mm_id = $id;
			if (!empty($mmd_model->content))
				$mmd_model->save();
		}

		$mmp_search = new MmParticipantsSearch();
		$mmp_provider = $mmp_search->search(Yii::$app->request->queryParams, $id);

		$mma_search = new MmAgendaSearch();
		$mma_provider = $mma_search->search(Yii::$app->request->queryParams, $id);

		$mmo_search = new MmOfferSearch();
		$mmo_provider = $mmo_search->search(Yii::$app->request->queryParams, $id);

		$mmd_search = new MmDecisionSearch();
		$mmd_provider = $mmd_search->search(Yii::$app->request->queryParams, $id);

		return $this->render('view', [
            'model' => $this->findModel($id),
			'mmp_model' => $mmp_model,
			'mmp_search' => $mmp_search,
			'mmp_provider' => $mmp_provider,

			'mma_model' => $mma_model,
			'mma_search' => $mma_search,
			'mma_provider' => $mma_provider,

			'mmo_model' => $mmo_model,
			'mmo_search' => $mmo_search,
			'mmo_provider' => $mmo_provider,

			'mmd_model' => $mmd_model,
			'mmd_search' => $mmd_search,
			'mmd_provider' => $mmd_provider
        ]);
    }

    /**
     * Creates a new MeetingMinutes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MeetingMinutes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MeetingMinutes model.
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
     * Deletes an existing MeetingMinutes model.
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
     * Finds the MeetingMinutes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MeetingMinutes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MeetingMinutes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
