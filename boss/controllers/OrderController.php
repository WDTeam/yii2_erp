<?php

namespace boss\controllers;
use core\models\finance_refund\FinanceRefundadd;
use core\models\customer\CustomerAddress;
use core\models\order\OrderWorkerRelation;
use core\models\worker\Worker;
use Yii;
use boss\components\BaseAuthController;
use boss\models\order\OrderSearch;
use boss\models\order\Order;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use core\models\Customer;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseAuthController
{
    public function actionCancelOrder()
    {   
        $orderid = yii::$app->request->get('orderid',1);
        $result = Order::cancel($orderid,Yii::$app->user->id);
        if($result==false) 
        {
            echo "order canlel module error";
        }
        $orderInfo = OrderSearch::getOne($orderid);
       // print_r($orderInfo);
        $FinanceRefundadd=new FinanceRefundadd;
        $FinanceRefundadd->finance_refund_pop_nub='123456789988';//第三方订单号，后期使用
        $FinanceRefundadd->finance_refund_tel='15172543897';//下单者电话
        $FinanceRefundadd->finance_refund_money='56.00';//退款金额
        $FinanceRefundadd->finance_refund_stype=1; //申请方式  1 用户取消订单 2 官方工作人员操作 3 其他
        $FinanceRefundadd->finance_refund_reason='有钱就是任性';//退款理由
        $FinanceRefundadd->finance_refund_discount='0';//优惠价格
         
        $FinanceRefundadd->finance_refund_pay_create_time=1435665333;//订单支付时间
        $FinanceRefundadd->finance_refund_pay_status=1;//支付状态 1支付 0 未支付 2 其他
        $FinanceRefundadd->finance_refund_pay_flow_num='77123';//我们系统订单号
        $FinanceRefundadd->finance_order_channel_id=1;//订单渠道id
        $FinanceRefundadd->finance_order_channel_title='美团团购';//订单渠道名称
         
        $FinanceRefundadd->finance_pay_channel_id=1;//支付渠道id
        $FinanceRefundadd->finance_pay_channel_title='美团团购';//支付渠道名称
         
        $FinanceRefundadd->finance_refund_worker_id=15;//服务阿姨uid
        $FinanceRefundadd->finance_refund_worker_tel='1517723454';//阿姨电话
        $FinanceRefundadd->isstatus=2; //1 取消 2 退款的 3 财务已经审核 4 财务已经退款 0 不确定
        $FinanceRefundadd->create_time=time(); //创建时间
        $FinanceRefundadd->is_del=0; //是否删除  0  正常 1 删除  默认是0
         
        $infodate=$FinanceRefundadd->add();
       var_dump($result);
    }

    public function actionCustomer()
    {
        $phone = Yii::$app->request->get('phone');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Customer::getCustomerInfo($phone);

    }

    public function actionCustomerAddress($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $address_list = Customer::getCustomerAddresses($id);
        $address = [];
        if(is_array($address_list)) {
            foreach ($address_list as $v) {
                $address[$v['id']] = $v;
            }
        }
        return $address;
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

    public function actionGetGoods()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $longitude = Yii::$app->request->get('lng');
        $latitude = Yii::$app->request->get('lat');
       return Order::getGoods($longitude,$latitude);
    }

    public function actionGetCity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $province_id = Yii::$app->request->get('province_id');
        return Order::getOnlineCityList($province_id);
    }

    public function actionGetCounty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $city_id = Yii::$app->request->get('city_id');
        return Order::getCountyList($city_id);
    }

    public function actionCoupons()
    {
        $id = Yii::$app->request->get('id');
        $service_id = Yii::$app->request->get('service_id');
        return '[
                {
                    "id": 1,
                    "coupon_name": "浼樻儬鍒�30",
                    "coupon_money": 30
                },
                {
                    "id": 2,
                    "coupon_name": "40浼樻儬鍒�",
                    "coupon_money": 40
                },
                {
                    "id": 3,
                    "coupon_name": "50浼樻儬鍒�",
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
     * 鑾峰彇寰呮墜宸ユ寚娲剧殑璁㈠崟
     * @return $this|static
     */
    public function actionGetWaitManualAssignOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order = OrderSearch::getWaitManualAssignOrder(Yii::$app->user->id,true);
        if($order){
            return
                [
                    'order'=>$order,
                    'ext_pay'=>$order->orderExtPay,
                    'ext_pop'=>$order->orderExtPop,
                    'ext_customer'=>$order->orderExtCustomer,
                    'ext_flag'=>$order->orderExtFlag,
                    'operation_long_time'=>Order::MANUAL_ASSIGN_lONG_TIME,
                    'booked_time_range'=>date('Y-m-d    H:i-',$order->order_booked_begin_time).date('H:i',$order->order_booked_end_time),
                ];
        }else{
            return false;
        }
    }

    /**
     * 鑾峰彇鍙寚娲鹃樋濮ㄧ殑鍒楄〃
     * @return array
     */
    public function actionGetCanAssignWorkerList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->get('order_id');
        $order = Order::findOne($order_id);

        $district_id = $order->district_id;
        try{
            $used_worker_list = Customer::getCustomerUsedWorkers($order->orderExtCustomer->customer_id);
            $used_worker_ids = [];
            if (is_array($used_worker_list)) {
                foreach ($used_worker_list as $v) {
                    $used_worker_ids[] = $v['worker_id'];
                }
            }
        }catch (Exception $e){

        }
        //鏍规嵁鍟嗗湀鑾峰彇闃垮Ж鍒楄〃 绗簩涓弬鏁� 1鑷湁 2闈炶嚜鏈�
        try {
            $worker_list = array_merge(Worker::getDistrictFreeWorker($district_id, 1, $order->order_booked_begin_time, $order->order_booked_end_time), Worker::getDistrictFreeWorker($district_id, 2, $order->order_booked_begin_time, $order->order_booked_end_time));
        }catch (Exception $e){
            return ['code'=>500,'msg'=>'鑾峰彇闃垮Ж鍒楄〃鎺ュ彛寮傚父锛�'];
        }
        $worker_ids = [];
        $workers = [];
        if (is_array($worker_list)) {
            foreach ($worker_list as $k => $v) {
                $worker_ids[] = $v['id'];
                $workers[$v['id']] = $worker_list[$k];
                $workers[$v['id']]['tag'] = in_array($v['id'], $used_worker_ids) ? '鏈嶅姟杩�' : "";
                $workers[$v['id']]['tag'] = ($v['id'] == $order->order_booked_worker_id) ? '鎸囧畾闃垮Ж' : $workers[$v['id']]['tag'];
                $workers[$v['id']]['order_booked_time_range'] = [];
                $workers[$v['id']]['memo'] = [];
                $workers[$v['id']]['status'] = [];
            }
            $worker_orders = OrderSearch::getListByWorkerIds($worker_ids, $order->order_booked_begin_time);
            foreach ($worker_orders as $v) {
                $workers[$v->orderExtWorker->worker_id]['order_booked_time_range'][] = date('H:i', $v->order_booked_begin_time) . '-' . date('H:i', $v->order_booked_end_time);
            }
            $order_worker_relations = OrderWorkerRelation::getListByOrderIdAndWorkerIds($order_id, $worker_ids);
            foreach ($order_worker_relations as $v) {
                $workers[$v->worker_id]['memo'][] = $v->order_worker_relation_memo;
                $workers[$v->worker_id]['status'][] = $v->order_worker_relation_status;
            }
        }
        return ['code'=>200,'data'=>$workers];

    }

    public function actionSearchAssignWorker()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->get('order_id');
        $phone =  Yii::$app->request->get('phone');
        $worker_name =  Yii::$app->request->get('worker_name');

        $order = Order::findOne($order_id);
        $used_worker_list = Customer::getCustomerUsedWorkers($order->orderExtCustomer->customer_id);
        $used_worker_ids = [];
        if (is_array($used_worker_list)) {
            foreach ($used_worker_list as $v) {
                $used_worker_ids[] = $v['worker_id'];
            }
        }
        //鏍规嵁鍟嗗湀鑾峰彇闃垮Ж鍒楄〃 绗簩涓弬鏁� 1鑷湁 2闈炶嚜鏈�
        try {
            $worker_list = Worker::searchWorker($worker_name, $phone);
        }catch (Exception $e){
            return ['code'=>500,'msg'=>'鑾峰彇闃垮Ж鍒楄〃鎺ュ彛寮傚父锛�'];
        }
        $worker_ids = [];
        $workers = [];
        if (is_array($worker_list)) {
            foreach ($worker_list as $k => $v) {
                $worker_ids[] = $v['id'];
                $workers[$v['id']] = $worker_list[$k];
                $workers[$v['id']]['tag'] = in_array($v['id'], $used_worker_ids) ? '鏈嶅姟杩�' : "";
                $workers[$v['id']]['tag'] = ($v['id'] == $order->order_booked_worker_id) ? '鎸囧畾闃垮Ж' : $workers[$v['id']]['tag'];
                $workers[$v['id']]['order_booked_time_range'] = [];
                $workers[$v['id']]['memo'] = [];
                $workers[$v['id']]['status'] = [];
            }
            $worker_orders = OrderSearch::getListByWorkerIds($worker_ids, $order->order_booked_begin_time);
            foreach ($worker_orders as $v) {
                $workers[$v->orderExtWorker->worker_id]['order_booked_time_range'][] = date('H:i', $v->order_booked_begin_time) . '-' . date('H:i', $v->order_booked_end_time);
            }
            $order_worker_relations = OrderWorkerRelation::getListByOrderIdAndWorkerIds($order_id, $worker_ids);
            foreach ($order_worker_relations as $v) {
                $workers[$v->worker_id]['memo'][] = $v->order_worker_relation_memo;
                $workers[$v->worker_id]['status'][] = $v->order_worker_relation_status;
            }
        }
        return ['code'=>200,'data'=>$workers];


    }



    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $list =  $dataProvider->query->all();
       // print_r($list);
       // exit;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'list' =>$list
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
            $model->order_booked_count = 120; //鏈嶅姟鏃堕暱鍒濆鍊�120鍒嗛挓
            $model->order_booked_worker_id=0; //涓嶆寚瀹氶樋濮�
            $model->orderBookedTimeRange = '08:00-10:00';//棰勭害鏃堕棿娈靛垵濮嬪��
            $model->order_pay_type = 1;//鏀粯鏂瑰紡 鍒濆鍊�
            $model->order_flag_sys_assign = 1;//鏄惁绯荤粺鎸囨淳
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 璁㈠崟鎸囨淳椤甸潰
     * @return string
     */
    public function actionAssign()
    {
        return $this->render('assign');
    }

    /**
     * 涓嶈兘鎸囨淳
     * @return array|bool
     */
    public function actionCanNotAssign()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->get('order_id');
        return Order::manualAssignUndone($order_id,Yii::$app->user->id,true);
    }
    
    /**
     * 鎸囨淳
     * @return array|bool
     */
    public function actionDoAssign()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->post('order_id');
        $worker_id =  Yii::$app->request->post('worker_id');
        return Order::manualAssignDone($order_id,$worker_id,Yii::$app->user->id,true);
    }


    /**
     * 娣诲姞璁㈠崟鍜岄樋濮ㄧ殑鍏崇郴淇℃伅
     *
     */
    public function actionCreateOrderWorkerRelation()
    {
        $order_id = Yii::$app->request->post('order_id');
        $worker_id = Yii::$app->request->post('worker_id');
        $memo = Yii::$app->request->post('memo');
        $status = Yii::$app->request->post('status');
        return OrderWorkerRelation::addOrderWorkerRelation($order_id,$worker_id,$memo,$status,Yii::$app->user->id);
    }


    public function actionSaveAddress($address_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $province_id = Yii::$app->request->post('province_id');
        $city_id = Yii::$app->request->post('city_id');
        $county_id = Yii::$app->request->post('county_id');
        $county_name = Yii::$app->request->post('county_name');
        $detail = Yii::$app->request->post('detail');
        $nickname = Yii::$app->request->post('nickname');
        $phone = Yii::$app->request->post('phone');
        $customer_id = Yii::$app->request->post('customer_id');
        if($address_id>0){
            //淇敼
            $address = CustomerAddress::updateAddress($address_id,$county_name,$detail,$nickname,$phone);
        }else{
            //娣诲姞
            $address = CustomerAddress::addAddress($customer_id,$county_name,$detail,$nickname,$phone);
        }
        if($address){
            return ['code'=>200,'data'=>$address];
        }else{
            return ['code'=>500,'error'=>'淇濆瓨澶辫触'];
        }
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
