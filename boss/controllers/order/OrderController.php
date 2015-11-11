<?php

namespace boss\controllers\order;

use boss\components\BaseAuthController;
use boss\models\order\OrderOtherDict;
use boss\models\order\OrderSearch;
use boss\models\order\Order;
use boss\models\order\OrderManualAssign;
use boss\models\order\OrderSearchIndex;

use core\models\customer\CustomerAddress;
use core\models\operation\coupon\CouponRule;
use core\models\order\OrderDispatcherKpi;
use core\models\order\OrderWorkerRelation;
use core\models\order\OrderStatusDict;
use core\models\customer\Customer;
use core\models\order\OrderStatusHistory;
use core\models\shop\Shop;
use core\models\system\SystemUser;


use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseAuthController
{
    public function actionTest()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Order::cancelByOrderCode('101511045457209', 1, OrderOtherDict::NAME_CANCEL_ORDER_CUSTOMER_OTHER_CAUSE, '测试');
//        return Order::serviceStart(2);
    }

    /**
     * 取消订单
     * @return bool
     */
    public function actionCancelOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        //TODO: Xiaobo
        $admin_id = Yii::$app->user->id;

        $params = yii::$app->request->post();
        $order_id = $params['order_id'];
        $cancel_type = $params['cancel_type'];
        $cancel_note = $params['cancel_note'];

        $result = Order::cancelByOrderId($order_id, $admin_id, $cancel_type, $cancel_note);

        if (is_null($result))
            return true;

        return $result;
    }

    /**
     * 根据手机号获取客户信息
     * @return array|bool
     */
    public function actionCustomer()
    {
        $phone = Yii::$app->request->get('phone');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customer = Customer::getCustomerInfo($phone);
        if (empty($customer)) {
            if (Customer::addCustomer($phone, 20)) {
                $customer = Customer::getCustomerInfo($phone);
            }
        }
        return $customer;

    }

    /**
     * 获取客户地址
     * @param $id
     * @return array
     */
    public function actionCustomerAddress($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $address_list = CustomerAddress::listAddress($id);
        $address = [];
        if (is_array($address_list)) {
            foreach ($address_list as $v) {
                $address[$v['id']] = $v;
            }
        }
        return $address;
    }

    /**
     * 获取常用阿姨
     * @param $id
     * @return array
     */
    public function actionCustomerUsedWorkers($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Customer::getCustomerUsedWorkers($id);
    }

    /**
     * 根据手机号查询阿姨
     * @return array
     */
    public function actionWorker()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $phone = Yii::$app->request->get('phone');
        return Order::getWorkerInfoByPhone($phone);
    }

    /**
     * 获取服务项目
     * @return array
     */
    public function actionGetGoods()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $longitude = Yii::$app->request->get('lng');
        $latitude = Yii::$app->request->get('lat');
        return Order::getGoods($longitude, $latitude);
    }

    /**
     * 根据省份获取城市
     * @return array
     */
    public function actionGetCity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $province_id = Yii::$app->request->get('province_id');
        return Order::getOnlineCityList($province_id);
    }

    /**
     * 根据城市获取区县
     * @return array
     */
    public function actionGetCounty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $city_id = Yii::$app->request->get('city_id');
        return Order::getCountyList($city_id);
    }

    /**
     * 获取可下单时间
     * @return array
     */
    public function actionGetTimeRangeList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_booked_count = Yii::$app->request->get('order_booked_count');
        $district_id = Yii::$app->request->get('district_id');
        $date = Yii::$app->request->get('date');
        return Order::getOrderBookedTimeRangeList($district_id, $order_booked_count, $date);
    }

    /**
     * 根据服务品类获取优惠券
     * @return array
     */
    public function actionCheckCouponCode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $coupon_code = Yii::$app->request->get('coupon_code');
        $customer_tel = Yii::$app->request->get('customer_phone');
        $service_type_id = Yii::$app->request->get('service_type_id');
        $service_item_id = Yii::$app->request->get('service_item_id');
        $city_id = Yii::$app->request->get('city_id');
        return CouponRule::get_is_coupon_status($coupon_code,$customer_tel,$service_type_id,$service_item_id,$city_id);
    }

    /**
     * 获取服务卡信息
     * @param $id
     * @return string
     */
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
        $is_mini_boss = Yii::$app->user->identity->isNotAdmin();
        if($is_mini_boss) {
            return OrderManualAssign::getMiniBossWaitAssignOrder(Yii::$app->user->id,Yii::$app->user->identity->shopDistrictIds);
        }else {
            return OrderManualAssign::getWaitAssignOrder(Yii::$app->user->id);
        }
    }

    /**
     * 获取可指派阿姨的列表
     * @return array
     */
    public function actionGetCanAssignWorkerList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id = Yii::$app->request->get('order_id');
        $is_mini_boss = Yii::$app->user->identity->isNotAdmin();
        if($is_mini_boss) {
            return OrderManualAssign::getMinibossCanAssignWorkerList($order_id,Yii::$app->user->identity->shopIds);
        }else{
            return OrderManualAssign::getCanAssignWorkerList($order_id);
        }

    }

    /**
     * 根据名称和手机号搜索阿姨列表
     * @return array
     */
    public function actionSearchAssignWorker()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id = Yii::$app->request->get('order_id');
        $phone = Yii::$app->request->get('phone');
        $worker_name = Yii::$app->request->get('worker_name');
        $is_mini_boss = Yii::$app->user->identity->isNotAdmin();
        if($is_mini_boss) {
            return OrderManualAssign::searchMiniBossAssignWorker($order_id,$worker_name,$phone,Yii::$app->user->identity->shopIds);
        }else{
            return OrderManualAssign::searchAssignWorker($order_id,$worker_name,$phone);
        }
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchParas = Yii::$app->request->getQueryParams();
        $searchModel = new OrderSearchIndex();
        $is_mini_boss = Yii::$app->user->identity->isNotAdmin();
        if($is_mini_boss){
            $dataProvider = $searchModel->search($searchParas);
            return $this->render('index-mini-boss', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'searchParas' => $searchParas,]);
        }else{
            $dataProvider = $searchModel->search($searchParas);
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'searchParas' => $searchParas,
                'cancelCause' => OrderOtherDict::getCancelOrderCauseType(),
                'cancelCustomerCause' => OrderOtherDict::getCancelOrderCustomerCause(),
                'cancelCompanyCause' => OrderOtherDict::getCancelOrderCompanyCause(),
            ]);
        }
    }

    /**
     * 通过搜索关键字获取门店信息
     * 联想搜索通过ajax返回
     * @param q string 关键字
     * @return result array 门店信息
     */
    public function actionShowShop($q = null)
    {
        Yii::$app->response->format = Yii\web\Response::FORMAT_JSON;
        
        $out = ['results' => ['id' => '', 'text' => '']];
        $condition = '';
        if ($q != null) {
            $condition = 'name LIKE "%' . $q . '%"';
        }
        $is_mini_boss = Yii::$app->user->identity->isNotAdmin();
        if($is_mini_boss){
            $shopResult = Shop::find()
                ->where($condition)
                ->andWhere(['id'=>Yii::$app->user->identity->shopIds])
                ->select('id, name AS text')->asArray()->all();
        }else{
            $shopResult = Shop::find()->where($condition)->select('id, name AS text')->asArray()->all();
        }
        $out['results'] = array_values($shopResult);
        //$out['results'] = [['id' => '1', 'text' => '门店'], ['id' => '2', 'text' => '门店2'], ['id' => '2', 'text' => '门店3']];
        return $out;
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
        if ($model->load($post)) {
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
        if ($model->load($post)) {
            if ($model->createNew($post)) {
                return $this->redirect(['view', 'id' => $model->order_code]);
            }
        } else {//init
            $model->order_booked_count = '2.0'; //服务时长初始值2小时
            $model->order_booked_worker_id = 0; //不指定阿姨
            $model->order_flag_sys_assign = 1;//是否系统指派
            $model->channel_id = 20;//订单渠道
            $model->pay_channel_id = 2;//支付渠道
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateBatch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $attributes = [
            'order_ip' => Yii::$app->request->userIP,
            'order_service_item_id' => 1,
            'order_src_id' => 1,
            'channel_id' => 20,
            'address_id' => 1,
            'customer_id' => 1,
            'order_customer_phone' => '18001305711',
            'admin_id' => Yii::$app->user->id,
            'order_pay_type' => 1,
            'order_is_use_balance' => 1,
            'order_booked_worker_id' => 0,
            'order_customer_need' => 'xxxxx',
            'order_customer_memo' => 'fffff',
            'order_flag_sys_assign' => 0,
        ];
        $booked_list = [
            [
                'order_booked_begin_time' => strtotime(date('Y-m-d 11:00:00')) + 86400,
                'order_booked_end_time' => strtotime(date('Y-m-d 13:00:00')) + 86400,
                'order_booked_count' => 2,
            ],
            [
                'order_booked_begin_time' => strtotime(date('Y-m-d 11:00:00')) + 86400 + 86400,
                'order_booked_end_time' => strtotime(date('Y-m-d 13:00:00')) + 86400 + 86400,
                'order_booked_count' => 2,
            ],
            [
                'order_booked_begin_time' => strtotime(date('Y-m-d 11:00:00')) + 86400 + 86400 + 86400,
                'order_booked_end_time' => strtotime(date('Y-m-d 13:00:00')) + 86400 + 86400 + 86400,
                'order_booked_count' => 2,
            ],
        ];
        return Order::createNewBatch($attributes, $booked_list);
    }

    /**
     * 查看并编辑订单
     * @param string $id
     * @return mixed
     */
    public function actionEdit($id)
    {

        $model = OrderSearch::getOneByCode($id);
        $post = Yii::$app->request->post();
        $model['admin_id'] = Yii::$app->user->id;

        $history = [];

        $createRecord = OrderStatusHistory::find()->where([
            'order_id' => $model->id,
            'order_status_dict_id' => OrderStatusDict::ORDER_INIT,
        ])->one();
        $history['creator_name'] = SystemUser::findOne(['id' => $createRecord['admin_id']])['username'];

        $payRecord = OrderStatusHistory::find()->where([
            'order_id' => $model->id,
            'order_status_dict_id' => OrderStatusDict::ORDER_WAIT_ASSIGN,
        ])->one();
        $history['pay_time'] = $payRecord ? date('Y-m-d H:i:s', $payRecord['created_at']) : null;

        if ($model->load($post)) {
            $post['Order']['admin_id'] = Yii::$app->user->id;
            $post['Order']['order_ip'] = Yii::$app->request->userIP;
            $post['Order']['order_customer_need'] = (isset($post['Order']['order_customer_need']) && is_array($post['Order']['order_customer_need'])) ? implode(',', $post['Order']['order_customer_need']) : ''; //客户需求

            //预约时间处理
            $time = explode('-', $post['Order']['orderBookedTimeRange']);
            $post['Order']['order_booked_begin_time'] = strtotime($post['Order']['orderBookedDate'] . ' ' . $time[0] . ':00');
            $post['Order']['order_booked_end_time'] = strtotime(($time[1] == '24:00') ? date('Y-m-d H:i:s', strtotime($post['Order']['orderBookedDate'] . '00:00:00 +1 days')) : $post['Order']['orderBookedDate'] . ' ' . $time[1] . ':00');

            if ($model->update($post)) {
                return $this->redirect(['edit', 'id' => $model->id]);
            }
        }

        return $this->render('edit', ['model' => $model, 'history' => $history]);
    }

    /**
     * ajax编辑订单
     * @return array
     */
    public function actionModify()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $attr = Yii::$app->request->post();
        $order = OrderSearch::getOne($attr['id']);
        if ($order->modify($attr)) {
            return ['status' => 1, 'info' => '修改成功'];
        } else {
            return ['status' => 0, 'info' => '修改失败'];
        }

    }


    /**
     * 订单指派页面
     * @return string
     */
    public function actionAssign()
    {
        $kpiModel = new OrderDispatcherKpi();
        $model = $kpiModel->queryHistoricalKpi(yii::$app->user->id,strtotime(date('y-m-d')));
        return $this->render('assign', [
            'model' => $model
        ]);
    }

    /**
     * 不能指派
     * @return array|bool
     */
    public function actionCanNotAssign()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id = Yii::$app->request->get('order_id');
        return Order::manualAssignUndone($order_id, Yii::$app->user->id, true);
    }

    /**
     * 指派
     * @return array|bool
     */
    public function actionDoAssign()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $order_id = Yii::$app->request->post('order_id');
        $worker_id = Yii::$app->request->post('worker_id');
        return Order::manualAssignDone($order_id, $worker_id, Yii::$app->user->id, true);
    }

    /**
     * 阿姨拒单
     * @return bool
     */
    public function actionWorkerRefuse()
    {
        $order_id = Yii::$app->request->post('order_id');
        $worker_id = Yii::$app->request->post('worker_id');
        $memo = Yii::$app->request->post('memo');
        return OrderWorkerRelation::workerRefuse($order_id, $worker_id, Yii::$app->user->id, $memo);
    }

    /**
     * 联系阿姨未响应
     * @return bool
     */
    public function actionWorkerContactFailure()
    {
        $order_id = Yii::$app->request->post('order_id');
        $worker_id = Yii::$app->request->post('worker_id');
        return OrderWorkerRelation::workerContactFailure($order_id, $worker_id, Yii::$app->user->id);
    }


    /**
     * 添加修改客户地址
     * @param $address_id
     * @return array
     */
    public function actionSaveAddress($address_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $province_id = Yii::$app->request->post('province_id');
        $city_id = Yii::$app->request->post('city_id');
        $county_id = Yii::$app->request->post('county_id');
        $province_name = Yii::$app->request->post('province_name');
        $city_name = Yii::$app->request->post('city_name');
        $county_name = Yii::$app->request->post('county_name');
        $detail = Yii::$app->request->post('detail');
        $nickname = Yii::$app->request->post('nickname');
        $phone = Yii::$app->request->post('phone');
        $customer_id = Yii::$app->request->post('customer_id');
        if ($address_id > 0) {
            //修改
            $address = CustomerAddress::updateAddress($address_id, $province_name, $city_name, $county_name, $detail, $nickname, $phone);
        } else {
            //添加
            $address = CustomerAddress::addAddress($customer_id, $province_name, $city_name, $county_name, $detail, $nickname, $phone);
        }
        if ($address) {
            return ['code' => 200, 'data' => $address];
        } else {
            return ['code' => 500, 'error' => '保存失败'];
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
     * @param string $code
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = OrderSearch::getOneByCode($code)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}