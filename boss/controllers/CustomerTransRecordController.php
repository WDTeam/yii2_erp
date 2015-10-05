<?php

namespace boss\controllers;

use common\models\CustomerTransRecordLog;
use Yii;
use common\models\CustomerTransRecord;
use boss\models\CustomerTransRecordSearch;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerTransRecordController implements the CRUD actions for CustomerTransRecord model.
 */
class CustomerTransRecordController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 创建交易记录
     * @param $data 数据
     */
    public function createRecord($data){
        //交易记录日志
        $TransRecordModel = new CustomerTransRecordLog();
        $TransRecordModel->save(false);
        $model = new CustomerTransRecord();
        //使用场景
        $model->scenario = $data['scenario'];
        $model->attributes = $data;
        return $model->add();
    }















    /**
     * Lists all CustomerTransRecord models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new CustomerTransRecordSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CustomerTransRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new CustomerTransRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerTransRecord;
        $data = Yii::$app->request->post();
        $data['CustomerTransRecord']['scenario'] = 4;
        if ($model->load(Yii::$app->request->post())) {
            $model = $this->createRecord($data['CustomerTransRecord']);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerTransRecord model.
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
     * Deletes an existing CustomerTransRecord model.
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
     * Finds the CustomerTransRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerTransRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerTransRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
