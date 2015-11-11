<?php

namespace boss\controllers\order;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use boss\models\order\OrderComplaint;
use boss\components\BaseAuthController;
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = false;
    	$model = new OrderComplaint();$oderarr = array();
    	$params = Yii::$app->request->post();
    	if(!empty($params) && is_array($params)){
    			$oderarr = $model->order_Complaint($params);
    			foreach ($oderarr as $val){
    				$_model = clone $model;
    				$result = $_model->backInsertOrderComplaint($val);
    			}
    	}
    	return $result;
    }
    /**
     * 返回渠道
     * @param unknown $num
     */
    public function actionChannel($num){
    	$model = new OrderComplaint();
    	return $model->channel($num);
    }
    /**
     * 返回投诉类型
     * @param unknown $dnum
     * @param unknown $num
     */
    public function actionCtype($dnum,$num){
    	$model = new OrderComplaint();
    	 return $model->ctype($dnum, $num);
    }
    /**
     * 返回部门
     * @param unknown $num
     */
    public function actionSection($num){
    	$model = new OrderComplaint();
    	 return $model->section($nums);
    }
    /**
     * 返回级别
     * @param unknown $num
     */
    public function actionLevel($num){
    	$model = new OrderComplaint();
    	 return $model->level($num);
    }
    public function actionTest(){
    	$data =array ( 'order_id' => 1, 'complaint_type' => 1, 'complaint_status' => 1, 'complaint_channel' => 1, 'complaint_phone' => '13689898989', 'complaint_section' => 1, 'complaint_assortment' => 0, 'complaint_level' => '0', 'complaint_content' => '评论内容', 'complaint_time' => 1447225657, 'updated_at' => 1447225657, 'created_at' => 1447225657, 'is_softdel' => 0, 'order_code' => '123445', );
    	$model = new OrderComplaint();
    	$result = $model->appModel($data);
    	var_dump($result);
    }
}
