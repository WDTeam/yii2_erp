<?php

namespace boss\controllers;

use Yii;
use boss\models\ComplaintOrder;
use boss\models\ComplaintOrderSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ComplaintOrderController implements the CRUD actions for ComplaintOrder model.
 */
class ComplaintOrderController extends BaseAuthController
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
     * Lists all ComplaintOrder models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $searchModel = new ComplaintOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render("index");
        /* return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); */
    }
public function actionAdd(){
	$model = new ComplaintOrder();
    	$arr = array('ComplaintOrder'=>array(
    			'order_id'=>'1234',
    			'worker_id'=>'123123',
    			'complaint_type'=>'1',
    			'complaint_phone'=>13800138000,
    			'complaint_section'=>'1',
    			'complaint_level'=>'2',
    			'complaint_content'=>'33241234231',
    			'complaint_time'=>'12332131'));
    	$model->load($arr);
    	$model->save();
    	//$result = $model->insertModel($arr);
    	exit();
    }

    /**
     * Displays a single ComplaintOrder model.
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
     * Creates a new ComplaintOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ComplaintOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ComplaintOrder model.
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
     * Deletes an existing ComplaintOrder model.
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
     * Finds the ComplaintOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ComplaintOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComplaintOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
