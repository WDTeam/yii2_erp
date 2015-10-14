<?php

namespace boss\controllers;

use common\models\CustomerAddress;
use core\models\order\OrderWorkerRelation;
use core\models\worker\Worker;
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
        $phone = Yii::$app->request->get('phone');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Customer::getCustomerInfo($phone);

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

    public function actionGetGoods()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $longitude = Yii::$app->request->get('lng');
        $latitude = Yii::$app->request->get('lat');
        $shop_district_info= OperationShopDistrictController::getCoordinateShopDistrict($longitude, $latitude);
        //TODO 临时数据 薛成江接口没有数据
        $shop_district_info = ['msg' => '', 'status' => 1, 'data' =>
            [
              'id' => 1,
              'operation_shop_district_name' => '西城区',
              'operation_city_id' => '110100',
              'operation_city_name' => '北京',
              'operation_area_id' => '110102',
              'operation_area_name' => '西城区'
            ]
        ];
        if(isset($shop_district_info['status']) && $shop_district_info['status']==1){
            $goods = OperationGoodsController::getGoodsList($shop_district_info['data']['operation_city_id'], $shop_district_info['data']['id']);
            //TODO 临时数据 薛成江接口没有数据
            $goods = [
                'msg' => '',
                'status' => 1,
                'data' => [
                    0 =>[
                          'operation_goods_id' =>'1',
                          'operation_shop_district_goods_name' => '家庭保洁',
                          'operation_shop_district_goods_introduction' => '家庭保洁',
                          'operation_shop_district_goods_price' => '25',
                          'operation_shop_district_goods_lowest_consume_num' =>'2',
                          'operation_shop_district_goods_lowest_consume' => '50',
                          'operation_shop_district_goods_market_price' => '30',
                          'created_at' => '1444413773'
                        ],
                  1 => [
                          'operation_goods_id' =>'2',
                          'operation_shop_district_goods_name' => '新居开荒',
                          'operation_shop_district_goods_introduction' => '新居开荒',
                          'operation_shop_district_goods_price' => '200',
                          'operation_shop_district_goods_lowest_consume_num' =>'2',
                          'operation_shop_district_goods_lowest_consume' => '50',
                          'operation_shop_district_goods_market_price' => '30',
                          'created_at' => '1444413773'
                        ]
                ]
            ];
            if(isset($goods['status'])&&$goods['status']==1){
                return ['code'=>200,'data'=>$goods['data']];
            }else{
                return ['code'=>500,'msg'=>'获取服务类别失败：没有匹配的服务'];
            }
        }else{
            return ['code'=>500,'msg'=>'获取服务类别失败：没有匹配的商圈'];
        }
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

    public function actionGetCanAssignWorkerList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->get('order_id');
        $order = Order::findOne($order_id);
        $address = CustomerAddress::getAddress($order->address_id);
        if(isset($address->customer_address_longitude) && isset($address->customer_address_latitude)) {
            //TODO 根据地址经纬度获取商圈
            $shop_district_info = OperationShopDistrictController::getCoordinateShopDistrict($address->customer_address_longitude, $address->customer_address_latitude);
            //TODO 接口无数据 以下为临时数据
            $shop_district_info = ['msg' => '', 'status' => 1, 'data' =>
                [
                    'id' => 1,
                    'operation_shop_district_name' => '西城区',
                    'operation_city_id' => '110100',
                    'operation_city_name' => '北京',
                    'operation_area_id' => '110102',
                    'operation_area_name' => '西城区'
                ]
            ];
            if(isset($shop_district_info['status']) && $shop_district_info['status']==1) {
                $district_id = $shop_district_info['data']['id'];
                //根据商圈获取阿姨列表 第二个参数 1自有 2非自有
                $used_worker_list = Customer::getCustomerUsedWorkers($order->orderExtCustomer->customer_id);
                $used_worker_ids = [];
                if (is_array($used_worker_list)) {
                    foreach ($used_worker_list as $v) {
                        $used_worker_ids[] = $v['worker_id'];
                    }
                }
                $worker_list = array_merge(Worker::getDistrictFreeWorker($district_id, 1, $order->order_booked_begin_time, $order->order_booked_end_time), Worker::getDistrictFreeWorker($district_id, 2, $order->order_booked_begin_time, $order->order_booked_end_time));
                $worker_ids = [];
                $workers = [];
                if (is_array($worker_list)) {
                    foreach ($worker_list as $k => $v) {
                        $worker_ids[] = $v['id'];
                        $workers[$v['id']] = $worker_list[$k];
                        $workers[$v['id']]['tag'] = in_array($v['id'], $used_worker_ids) ? '服务过' : "";
                        $workers[$v['id']]['tag'] = ($v['id'] == $order->order_booked_worker_id) ? '指定阿姨' : $workers[$v['id']]['tag'];
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
            }else{
                return ['code'=>500,'msg'=>'获取阿姨列表失败：没有匹配到商圈'];
            }
        }else{
            return ['code'=>500,'msg'=>'获取阿姨列表失败：没有匹配到经纬度'];
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

    /**
     * 订单指派页面
     * @return string
     */
    public function actionAssign()
    {
        return $this->render('assign');
    }

    /**
     * 不能指派
     * @return array|bool
     */
    public function actionCanNotAssign()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id =  Yii::$app->request->get('order_id');
        return Order::manualAssignUndone($order_id,Yii::$app->user->id,true);
    }

    /**
     * 指派
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
     * 添加订单和阿姨的关系信息
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
