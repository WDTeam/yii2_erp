<?php

namespace boss\controllers;

use Yii;
use boss\components\BaseAuthController;
use boss\models\order\OrderSearch;
use boss\models\order\Order;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use core\models\Customer;
/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseAuthController
{

    public function actionCustomer()
    {
//        $phone = Yii::$app->request->get('phone');
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return Customer::getCustomerInfo($phone);

        return '{"id":1,"customer_balance":"1000"}';
    }

    public function actionCustomerAddress($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Customer::getCustomerAddresses($id);
    }

    public function actionCustomerUsedWorkers($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
       return Customer::getCustomerUsedWorkers($id);
    }

    public function actionWorker()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $phone = Yii::$app->request->get('phone');
        return Order::getWorkerInfoByPhone($phone);
    }

    public function actionCoupons()
    {
        $id = Yii::$app->request->get('id');
        $service_id = Yii::$app->request->get('service_id');
        return '[
                {
                    "id": 1,
                    "coupon_name": "优惠券30",
                    "coupon_money": 30
                },
                {
                    "id": 2,
                    "coupon_name": "40优惠券",
                    "coupon_money": 30
                },
                {
                    "id": 3,
                    "coupon_name": "50优惠券",
                    "coupon_money": 50
                }
            ]';
    }

    public function actionCards($id)
    {
        return '[
                {
                    "id": 1,
                    "card_code": "1234567890",
                    "card_money": 1000
                },
                {
                    "id": 2,
                    "card_code": "9876543245",
                    "card_money": 3000
                },
                {
                    "id": 3,
                    "card_code": "3840959205",
                    "card_money": 5000
                }
            ]';
    }


    /**
     * 获取待手工指派的订单
     * @return $this|static
     */
    public function actionGetWaitManualAssignOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order = OrderSearch::getWaitManualAssignOrder(Yii::$app->user->id,true);
        if($order){
            $oper_long_time = 900; //TODO 客服最大执行时间
            return
                [
                    'order'=>$order,
                    'ext_pay'=>$order->orderExtPay,
                    'ext_pop'=>$order->orderExtPop,
                    'ext_customer'=>$order->orderExtCustomer,
                    'ext_flag'=>$order->orderExtFlag,
                    'oper_long_time'=>$oper_long_time,
                    'booked_time_range'=>date('Y-m-d    H:i-',$order->order_booked_begin_time).date('H:i',$order->order_booked_end_time),
                ];
        }else{
            return false;
        }
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($model->load($post)) {
            $post['Order']['admin_id'] = Yii::$app->user->id;
            $post['Order']['order_ip'] = ip2long(Yii::$app->request->userIP);

            if ($model->update($post)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('view', ['model' => $model]);

    }


    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();
        $post = Yii::$app->request->post();
        if($model->load($post)){
            if ($model->createNew($post)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else{//init
            $model->order_service_type_id = 1; //服务类型默认值
            $model->order_booked_count = 120; //服务市场初始值120分钟
            $model->order_booked_worker_id=0; //不指定阿姨
            $model->orderBookedTimeRange = '08:00-10:00';//预约时间段初始值
            $model->order_pay_type = 1;//支付方式 初始值
            $model->channel_id = 1;//默认渠道
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAssign()
    {
        return $this->render('assign');
    }


    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
