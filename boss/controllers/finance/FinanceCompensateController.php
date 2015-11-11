<?php

namespace boss\controllers\finance;

use core\models\finance\FinanceCompensate as FinanceCompensateSearch;
use core\models\order\OrderComplaint;
use core\models\customer\Customer;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * FinanceCompensateController implements the CRUD actions for FinanceCompensate model.
 */
class FinanceCompensateController extends Controller
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
     * Lists all FinanceCompensate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->load($requestParams);
        $searchModel->is_softdel = FinanceCompensateSearch::FINANCE_COMPENSATE_NO_DELETE;
        $dataProvider = $searchModel->search($requestParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * Lists all FinanceCompensate models.
     * @return mixed
     */
    public function actionFinanceConfirmIndex()
    {
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        if(isset($requestParams['worker_tel'])){
            $searchModel->worker_id = $requestParams['worker_tel'];
        }
        $searchModel->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_INIT;
        $searchModel->is_softdel = FinanceCompensateSearch::FINANCE_COMPENSATE_NO_DELETE;
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('financeConfirmIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
     public function actionReviewFailedReason(){
        $searchModel = new FinanceCompensateSearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel->id = $requestParams['id'];
        return $this->renderAjax('reviewFailedReason', [
            'model' => $searchModel,
        ]);
    }

    /**
     * Displays a single FinanceCompensate model.
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
     * 记录审核结果
     * @param type $id
     * @return type
     */
    public function actionReview($id,$is_ok){
        $model = $this->findModel($id);
        $requestParams = Yii::$app->request->getQueryParams();
        if(isset($requestParams['comment'])){
            $model->comment = $requestParams['comment'];
        }
        if($is_ok == 1){
            $model->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_PASSED;
        }else{
            $model->finance_compensate_status = FinanceCompensateSearch::FINANCE_COMPENSATE_REVIEW_FAILED;
        }
        $model->save();
        return $this->redirect(['finance-confirm-index']);
    }

    /**
     * Creates a new FinanceCompensate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceCompensateSearch;
        $postParams = null;
        $id = null;
        if(Yii::$app->request->post() != null){
            $postParams = Yii::$app->request->post();
            if(isset($postParams['id'])){
                $id = $postParams['id'];
            }
        }
        $getParams = null;
        if(Yii::$app->request->get() != null){
            $getParams = Yii::$app->request->get();
            if(isset($getParams['id'])){
                $id = $getParams['id'];
            }
        }
        $isNewRecord = true;
        if(!empty($id)){
            $model = $this->findModel($id);
            $isNewRecord = $model->isNewRecord;
        }
        $order_complaint_id = null;
        if(isset($getParams['order_complaint_id'])){
            $order_complaint_id = $getParams['order_complaint_id'];
        }
        $model->finance_complaint_id = $order_complaint_id;
        if ($model->load($postParams)) {
            $model->created_at = time();
            $model->finance_compensate_code = FinanceCompensateSearch::getCompensateApplyCode();
            $model->finance_compensate_money = $model->finance_compensate_total_money;
            $order_complaint = OrderComplaint::findOne($order_complaint_id);
            $model->order_id = $order_complaint->order_id;
            $model->order_code = $order_complaint->order_code_number;
            $model->finance_complaint_code = $order_complaint->complaint_code_number;
            $orderWorkerInfo = $order_complaint->order->orderExtWorker;
            $model->worker_id = $orderWorkerInfo->worker_id;
            $model->worker_tel = $orderWorkerInfo->order_worker_phone;
            $model->worker_name = $orderWorkerInfo->order_worker_name;
            $orderCustomerInfo = $order_complaint->order->orderExtCustomer;
            $model->customer_id = $orderCustomerInfo->customer_id;
            $customer = Customer::getCustomerById($orderCustomerInfo->customer_id);
            $model->customer_name = $customer->customer_name;
//            if(!empty($postParams) && isset($postParams['finance_compensate_coupon'])){
//                $finance_compensate_coupon_arr = $postParams['finance_compensate_coupon'];
//                $finance_compensate_coupon_money_arr = [];
//                $finance_compensate_coupon = null;
//                $finance_compensate_coupon_money = null;
//                foreach($finance_compensate_coupon_arr as $key=>$value){
//                    if(empty($value)){
//                        unset($finance_compensate_coupon_arr[$key]);
//                        continue;
//                    }
//                    $finance_compensate_coupon_money_arr[] = 50;
//                }
//                 if(count($finance_compensate_coupon_arr)> 0){
//                     $finance_compensate_coupon = implode(";", $finance_compensate_coupon_arr);
//                     $finance_compensate_coupon_money = implode(";", $finance_compensate_coupon_money_arr);
//                     $model->finance_compensate_coupon = $finance_compensate_coupon;
//                     $model->finance_compensate_coupon_money = $finance_compensate_coupon_money;
//                 }
//            }
            $model->save();
            if($isNewRecord){
                return $this->redirect(['/order/order-complaint/']);
            }else{
                return $this->redirect(['index']);
            }
        } else {
            if(!empty($id)){
                 $model = $this->findModel($id);
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceCompensate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/finance/finance-compensate/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceCompensate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_softdel = FinanceCompensateSearch::FINANCE_COMPENSATE_DELETE;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceCompensate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceCompensate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceCompensateSearch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   
}
