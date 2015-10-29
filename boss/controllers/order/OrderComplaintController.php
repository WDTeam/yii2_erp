<?php

namespace boss\controllers\order;

use Yii;
use boss\models\order\OrderComplaint;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use boss\models\order\OrderComplaintSearch;
use core\models\finance\FinanceCompensate;

/**
 * OrderComplaintController implements the CRUD actions for OrderComplaint model.
 */
class OrderComplaintController extends BaseAuthController
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
     * Lists all OrderComplaint models.
     * @return mixed
     */
    public function actionIndex()
    {   
    	$session = Yii::$app->session;
    	$searchModel = new OrderComplaintSearch();
    	$orderComplaint = new OrderComplaint();
    	$comStatus = OrderComplaint::ComplaintStatus();
    	$channel = OrderComplaint::complaint_channel();
    	$dev = OrderComplaint::Department();
    	$comLevel = OrderComplaint::ComplaintLevel();
    	$comType = OrderComplaint::ComplaintType();
    	$params = Yii::$app->request->getQueryParams();
    	$url = $searchModel->urlParameterProcessing($params);
    	$dataProvider = $searchModel->search($params);
    	return $this->render('index', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'comStatus' => $comStatus,
    			'comLevel' => $comLevel,
    			'comType' => $comType,
    			'devpart' => $dev,
    			'channel' => $channel,
    			'params' => $params,
    			'url' => $url
    	]);
        
    }
	public function actionAdd(){
	$model = new OrderComplaint();
    	$arr = array('OrderComplaint'=>array(
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
     * Displays a single OrderComplaint model.
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
     * Creates a new OrderComplaint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$model = new FinanceCompensate();
        $params = Yii::$app->request->getQueryParams();
        if(!empty($params['id'])){
        	$id = intval($params['id']);
        	$orderComplaintModel = $this->findModel($id);
        	
        	return $this->render('create', [
        			'orderComplaintModel' => $orderComplaintModel,'model'=>$model,
        	]);
        }else{
        	false;
        }
    }

    /**
     * Updates an existing OrderComplaint model.
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
     * Deletes an existing OrderComplaint model.
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
     * Finds the OrderComplaint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderComplaint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderComplaint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 后端订单投诉添加业务逻辑
     * @return boolean
     */
    public function actionBack(){
    	$model = new OrderComplaint();
    	$params = Yii::$app->request->post();
    	if(!empty($params) && is_array($params)){
    			$result = $model->backInsertComplaint($params);
    	}
    	return $result;
    }
}
