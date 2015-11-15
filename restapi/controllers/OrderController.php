<?php

namespace restapi\controllers;

use core\models\order\OrderOtherDict;
use \core\models\order\OrderPush;
use \core\models\order\Order;
use \core\models\order\OrderSearch;
use \core\models\order\OrderStatusDict;
use \core\models\customer\CustomerAccessToken;
use \core\models\customer\CustomerAddress;
use \core\models\worker\WorkerAccessToken;
use \core\models\worker\Worker;
use core\models\operation\OperationShopDistrictCoordinate;
use \core\models\operation\OperationShopDistrictGoods;
use \core\models\operation\OperationOrderChannel;
use \core\models\operation\OperationCity;
use \core\models\order\OrderStatusHistory;
use restapi\models\alertMsgEnum;
use \restapi\models\EjjEncryption;
use yii\web\Response;
use Yii;

class OrderController extends \restapi\components\Controller
{

    public $workerText = array(1 => '指定阿姨订单数,待抢单订单数', '指定阿姨订单列表', '待抢单订单列表');

    /**
     * @api {POST} /order/create-order [POST] /order/create-order(100%)
     * @apiDescription  创建订单 (谢奕)
     *
     * @apiName ActionCreateOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_service_item_id 服务项目id
     * @apiParam {String} order_channel_name     订单渠道名称
     * @apiParam {String} order_booked_begin_time 服务开始时间 时间戳  如 *'2015-10-15 10:10:10'
     * @apiParam {String} order_booked_end_time 服务结束时间   时间戳  如 *'2015-10-15 12:10:10'
     * @apiParam {String} order_customer_phone 用户手机号
     * @apiParam {String} order_booked_count 服务时长
     * @apiParam  {int}   pay_channel_key     支付渠道
     * @apiParam {String} address_id 订单地址id
     * @apiParam {String} [address] 订单地址
     * @apiParam {String} [city]城市
     * @apiParam {String} [order_pop_order_code] 第三方订单号
     * @apiParam {String} [order_pop_group_buy_code] 第三方团购号
     * @apiParam {Integer} [order_pop_order_money] 第三方订单金额,预付金额
     * @apiParam {String} [coupon_id] 优惠劵id
     * @apiParam {String} [order_booked_worker_id] 指定阿姨id
     * @apiParam {Number} [order_customer_need] 客户需求
     * @apiParam {String} [order_customer_memo] 客户备注
     * @apiParam {Integer} [order_is_use_balance] 是否使用余额 1 使用 0 不适用 默认1
     *
     *
     * @apiSuccess {Object} order 成功订单对象.
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/order/action-append-order
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * {
     *  "code": "1",
     *  "msg": "创建订单成功",
     *  "ret": {
     *    "id":"订单id",
     *    "order_code":"订单号"
     *   }
     *  "alertMsg": "创建订单成功,请重新登录"
     *  }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 401,
     *        "msg": "用户无效,请先登录",
     *        "ret": {},
     *        "alertMsg": "用户认证已经过期,请重新登录"
     *     }
     *
     */
    public function actionCreateOrder()
    {
        $args = Yii::$app->request->post() or $args = json_decode(Yii::$app->request->getRawBody(), true);
        $attributes = [];
        @$token = $args['access_token'];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $attributes['customer_id'] = $user->id;

        if (empty($args['order_service_item_id'])) {
            return $this->send(null, "请输入服务项目id", 0, 200, null, alertMsgEnum::orderServiceItemIdFaile);
        }
        $attributes['order_service_item_id'] = $args['order_service_item_id'];

        #开始时间
        if (empty($args['order_booked_begin_time'])) {
            return $this->send(null, "数据不完整,请输入初始时间", 0, 200, null, alertMsgEnum::orderBookedBeginTimeFaile);
        }
        $attributes['order_booked_begin_time'] = strtotime($args['order_booked_begin_time']);

        #结束时间
        if (empty($args['order_booked_end_time'])) {
            return $this->send(null, "数据不完整,请输入完成时间", 0, 200, null, alertMsgEnum::orderBookedEndTimeFaile);
        }
        $attributes['order_booked_end_time'] = strtotime($args['order_booked_end_time']);

        $attributes['order_channel_name'] = isset($args['order_channel_name']) ? $args['order_channel_name'] : "";

        if ($attributes['order_booked_end_time'] <= $attributes['order_booked_begin_time']) {
            return $this->send(null, "对不起,开始时间不能大于等于结束时间", 0, 200, null, alertMsgEnum::orderStartEndTime);
        }

        if (empty($args['order_customer_phone']) || !is_numeric($args['order_customer_phone'])) {
            return $this->send(null, "对不起，用户手机错误", 0, 200, null, alertMsgEnum::customerPhoneFaile);
        }

        #支付渠道
        $attributes['pay_channel_key'] = isset($args['pay_channel_key']) ? $args['pay_channel_key'] : "";

        #服务时长
        if (empty($args['order_booked_count'])) {
            return $this->send(null, "数据不完整,请输入服务时长", 0, 200, null, alertMsgEnum::orderPayTypeFaile);
        }
        $attributes['order_booked_count'] = $args['order_booked_count'];


        if (isset($args['address_id'])) {
            $attributes['address_id'] = $args['address_id'];
        } elseif (isset($args['address']) && isset($args['city'])) {
            //add address into customer and return customer id
            $address = $args['address'];
            $city = $args['city'];
            $model = CustomerAddress::addAddressForPop($user->id, $args['order_customer_phone'], $city, $address);
            if (!empty($model)) {
                $attributes['address_id'] = $model->id;
            } else {
                return $this->send(null, "地址数据不完整,请输入常用地址id或者城市,地址名（包括区）", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
            }
        } else {
            return $this->send(null, "数据不完整,请输入常用地址id或者城市,地址名", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
        }

        if (isset($args['order_pop_order_code'])) {
            $attributes['order_pop_order_code'] = $args['order_pop_order_code'];
        }
        if (isset($args['order_pop_order_money'])) {
            $attributes['order_pop_order_money'] = $args['order_pop_order_money'];
        }
        if (isset($args['order_pop_group_buy_code'])) {
            $attributes['order_pop_group_buy_code'] = $args['order_pop_group_buy_code'];
        }
        if (isset($args['coupon_id'])) {
            $attributes['coupon_id'] = $args['coupon_id'];
        }


        if (isset($args['order_booked_worker_id'])) {
            $attributes['order_booked_worker_id'] = $args['order_booked_worker_id'];
        }
        if (isset($args['order_customer_need'])) {
            $attributes['order_customer_need'] = $args['order_customer_need'];
        }
        if (isset($args['order_customer_memo'])) {
            $attributes['order_customer_memo'] = $args['order_customer_memo'];
        }
        $attributes['order_is_use_balance'] = 1;
        if (isset($args['order_is_use_balance'])) {
            $attributes['order_is_use_balance'] = $args['order_is_use_balance'];
        }
        $attributes['order_ip'] = Yii::$app->getRequest()->getUserIP();
        $attributes['admin_id'] = Order::ADMIN_CUSTOMER;

        try {
            $order = new Order();
            $is_success = $order->createNew($attributes);

            if (implode(',', $order->errors['error_code']) == 540111) {
                $errorMsg = '您有未支付订单,请您先支付或取消再创建新订单';
            } else {
                $errorMsg = '创建订单失败';
            }

            if ($is_success) {
                $ret = array(
                    "id" => $order->id,
                    "order_code" => $order->order_code
                );
                return $this->send($ret, '创建订单成功', 1, 200, null, alertMsgEnum::orderCreateSuccess);
            } else {
                return $this->send($order->errors['error_code'], '创建订单失败', 1024, 200, null, $errorMsg . '[' . implode(',', $order->errors['error_code']) . ']');
            }
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, $errorMsg . '[' . implode(',', $order->errors['error_code']) . ']');
        }
    }

    /**
     * @api {POST} /order/create-value-add-order [POST] /order/create-value-add-order(100%)
     * @apiDescription  创建增值服务订单 (田玉星)
     *
     * @apiName actionCreateValueAddOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_service_item_id 服务项目id
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} order_booked_begin_time 服务开始时间 时间戳  如 '1443695400'
     * @apiParam {String} address_id 服务地址ID
     * @apiParam {String} order_customer_need 客户
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * {
     *  "code": "1",
     *  "msg": "创建订单成功",
     *  "ret": {
     *    "id":"订单id",
     *    "order_code":"订单号"
     *   }
     *  "alertMsg": "创建订单成功"
     *  }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 401,
     *        "msg": "用户无效,请先登录",
     *        "ret": {},
     *        "alertMsg": "用户认证已经过期,请重新登录"
     *     }
     *
     */
    public function actionCreateValueAddOrder()
    {
        $args = Yii::$app->request->post() or $args = json_decode(Yii::$app->request->getRawBody(), true);
        $attributes = [];
        if (!isset($args['access_token']) || !$args['access_token']) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        //验证用户登录情况
        try {
            $user = CustomerAccessToken::getCustomer($args['access_token']);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::userLoginFailed);
        }
        if (!$user) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        //填写服务项目
        if (!isset($args['order_service_item_id']) || !intval($args['order_service_item_id'])) {
            return $this->send(null, "请输入服务项目id", 0, 200, null, alertMsgEnum::orderServiceItemIdFaile);
        }

        //服务开始时间/阿姨上门时间
        if (!isset($args['order_booked_begin_time']) || !$args['order_booked_begin_time']) {
            return $this->send(null, "数据不完整,请输入初始时间", 0, 200, null, alertMsgEnum::orderBookedBeginTimeFaile);
        }
        //所在地址
        if (!isset($args['address_id']) || !intval($args['address_id'])) {
            return $this->send(null, "数据不完整,请输入常用地址ID", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
        }

        $attributes['address_id'] = intval($args['address_id']);
        $attributes['customer_id'] = $user->id; //登录用户ID
        $attributes['order_service_item_id'] = intval($args['order_service_item_id']); //服务品类ID
        $attributes['order_booked_begin_time'] = intval($args['order_booked_begin_time']);
        $attributes['order_booked_end_time'] = $attributes['order_booked_begin_time'] + 10800; //服务结束时间
        $attributes['order_booked_count'] = 3; //服务时长
        $attributes['order_channel_name'] = isset($args['order_channel_name']) ? $args['order_channel_name'] : ""; //下单渠道 
        $attributes['pay_channel_key'] = 'PAY_CHANNEL_EJJ_CASH_PAY'; //现金支付
        $attributes['order_customer_need'] = isset($args['order_customer_need']) ? $args['order_customer_need'] : ""; //客户需求
        $attributes['order_ip'] = Yii::$app->getRequest()->getUserIP();
        //创建订单
        try {
            $attributes['admin_id'] = Order::ADMIN_CUSTOMER;
            $order = new Order();
            $is_success = $order->createNew($attributes);
            $ret = array(
                "id" => $order->id,
                "order_code" => $order->order_code
            );

            if ($is_success) {
                return $this->send($ret, '创建订单成功', 1, 200, null, alertMsgEnum::orderCreateSuccess);
            } else {
                $msgErrors = $order->errors;
                return $this->send($order->errors, '创建订单失败', 0, 200, null, '创建订单失败[' . implode(',', $order->errors['error_code']) . ']');
            }
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, '创建订单失败[' . implode(',', $order->errors['error_code']) . ']');
        }
    }

    /**
     * @api {GET} /order/check-district-goods [GET] /order/check-district-goods(100%)
     * @apiDescription  检查当前商圈是否已经开通选择的服务 (田玉星)
     *
     * @apiName actionCheckDistrictGoods
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_service_item_id 服务项目id
     * @apiParam {String} address_longitude 填写地址的经度
     * @apiParam {String} address_latitude 填写地址的纬度
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} city_name 当前城市名称 
     * @apiParam {String} [address_id] 用户地址ID
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "当前商圈内包含该服务",
     *       "ret": {},
     *       "alertMsg": "当前商圈包含该服务"
     *   }
     *
     * @apiErrorExample Error-Response:
     *   {
     *       "code": 0,
     *       "msg": "该服务不在当前地址商圈内",
     *       "ret": {},
     *       "alertMsg": "该服务不在当前商圈内"
     *   }
     *
     */
    public function actionCheckDistrictGoods()
    {
        $param = Yii::$app->request->get();
        if (!isset($param['access_token']) || !$param['access_token']) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        //验证用户登录情况
        $user = CustomerAccessToken::getCustomer($param['access_token']);
        if (!$user) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        if (!isset($param['order_service_item_id']) || !$param['order_service_item_id']) {
            return $this->send(null, "请输入服务项目id", 0, 200, null, alertMsgEnum::orderServiceItemIdFaile);
        }
        //判断商圈地址
        if (!isset($param['address_longitude']) || !$param['address_longitude']) {
            return $this->send(null, "address_longitude经度参数错误", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
        }
        if (!isset($param['address_latitude']) || !$param['address_latitude']) {
            return $this->send(null, "address_latitude维度参数错误", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
        }
        if (!isset($param['city_name']) || !$param['city_name']) {
            return $this->send(null, "城市名称错误", 0, 200, null, alertMsgEnum::orderAddressIdFaile);
        }

        $cityID = OperationCity::getCityId(trim($param['city_name']));
        if (!$cityID) {
            return $this->send(null, "该城市未开通", 0, 200, null, alertMsgEnum::orderCityDistrictFaile);
        }
        try {
            //TODO:田玉星觉得产品设计有问题，所以留取两种方案
            if (isset($param['address_id']) && intval($param['address_id'])) {
                $addressModel = CustomerAddress::getAddress(intval($param['address_id']));
                if ($addressModel) {
                    $param['address_longitude'] = $addressModel->customer_address_longitude;
                    $param['address_latitude'] = $addressModel->customer_address_latitude;
                }
            }
            $shopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($param['address_longitude'], $param['address_latitude']);
            if (!OperationShopDistrictGoods::getShopDistrictGoodsInfo($cityID, $shopDistrictInfo['operation_shop_district_id'], intval($param['order_service_item_id']))) {
                return $this->send(null, "该服务不在当前地址商圈内", 0, 200, null, alertMsgEnum::orderShopDistrictGoodsFaile);
            }
            return $this->send(null, "当前商圈内包含该服务", 1, 200, null, alertMsgEnum::orderShopDistrictGoodsSuccess);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
    }

    /**
     * @api {GET} /order/orders [GET] /order/orders (100%)
     * @apiDescription 查询用户订单 (谢奕)
     *
     * @apiName actionOrders
     * @apiGroup Orders
     *
     * @apiParam {String} access_token 用户令牌
     * @apiParam {String} order_channel_name      订单渠道名称 
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间 如 *'1443695400'
     * @apiParam {String} [to] 结束时间 如 *'1443695400'
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": "1",
     *    "msg": "操作成功",
     *    "ret": {
     *      "limit": "1",
     *       "page_total": 4,
     *       "offset": 0,
     *       "orders": [
     *        {
     *          "id": "订单id",
     *          "order_code": "订单号",
     *          "order_parent_id": "0",
     *          "order_is_parent": "0",
     *          "created_at": "1445347126",
     *          "updated_at": "1445347126",
     *          "isdel": "0",
     *          "ver": "3",
     *          "version": "3",
     *          "order_ip": "58.135.77.96",
     *          "order_service_type_id": "1",
     *           "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *          "order_src_id": "1",
     *          "order_src_name": "BOSS",
     *          "channel_id": "20",
     *          "order_channel_name": "后台下单",
     *          "order_unit_money": "20.00",
     *          "order_money": "40.00",
     *          "order_pay_type": "支付方式",
     *          "order_booked_count": "120",
     *          "order_booked_begin_time": "1446249600",
     *          "order_booked_end_time": "1446256800",
     *          "address_id": "397",
     *          "district_id": "3",
     *          "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *          "order_booked_worker_id": "0",
     *          "checking_id": "0",
     *          "order_cs_memo": "",
     *          "order_id": "2",
     *          "order_before_status_dict_id": "2",
     *          "order_before_status_name": "已支付",
     *          "order_status_dict_id": "3",
     *          "order_status_name": "已开始智能指派"
     *           }
     *    ]
     *  },
     * "alertMsg": "操作成功"
     *  }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionOrders()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }

        $channels = null;
        if (isset($args['channels'])) {
            $channels = explode(".", $args['channels']);
        }

        @$isAsc = $args['is_asc'];
        if (is_null($isAsc)) {
            $isAsc = true;
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $page = 1;
        if (isset($args['page'])) {
            $page = $args['page'];
        }
        $offset = ($page - 1) * $limit;
        @$from = $args['from'];
        @$to = $args['to'];
        $args["oc.customer_id"] = $user->id;
        $args['order_parent_id'] = 0;
        if ($limit <= 0) {
            $limit = 1;
        }
        try {
            $orderSearch = new OrderSearch();
            $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus);
            $orders = $orderSearch->searchOrdersWithStatus($args, $isAsc, $offset, $limit, $orderStatus, $channels, $from, $to);

            #获取周期订单子订单
            foreach ($orders as $key => $val) {
                if (!empty($val['order_batch_code'])) {
                    $arry = array('order_batch_code' => $val['order_batch_code'], 'order_parent_id' => null);
                    $subOrder = $orderSearch->searchOrdersWithStatus($arry, true, $offset, 100, $orderStatus, $channels, $from, $to, 'order.order_booked_begin_time');
                    $orders[$key]['sub_order'] = $subOrder;
                }
            }

            $ret = [];
            $ret['limit'] = $limit;
            $ret['page_total'] = ceil($count / $limit);
            $ret['page'] = $page;
            $ret['orders'] = $orders;

            $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderGetOrdersSuccess);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderGetOrdersFaile);
        }
    }

    /**
     * @api {GET} /order/orders-count [GET] /order/orders-count(100%)
     *
     * @apiName actionOrdersCount
     * @apiGroup Order
     * @apiDescription 获得用户各种状态的订单数量 （谢奕）周期订单作为一个订单来统计[当前逻辑]
     *
     * @apiParam {String} access_token 用户令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {int} [id] 订单id
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [from] 开始时间 时间戳   如 *'1443695400'
     * @apiParam {String} [to] 结束时间   时间戳   如 *'1443695400'
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "code": "1",
     *      "msg": "操作成功",
     *      "ret": {
     *          "count": "4"
     *      },
     *       "alertMsg": "操作成功"
     *     }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionOrdersCount()
    {

        $args = Yii::$app->request->get() or $args = json_decode(Yii::$app->request->getRawBody(), true);

        if (!isset($args['access_token']) || !$args['access_token']) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        $user = CustomerAccessToken::getCustomer($args['access_token']);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }
        $channels = null;
        if (isset($args['channels'])) {
            $channels = explode(".", $args['channels']);
        }
        @$from = $args['from'];
        @$to = $args['to'];
        $args["oc.customer_id"] = $user->id;
        $args['order_parent_id'] = 0;

        $orderSearch = new OrderSearch();
        $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus, $channels, $from, $to);
        $ret['count'] = $count;
        return $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderGetOrderCountSuccess);
    }

    /**
     * @api {GET} /order/worker-orders [GET] /order/worker-orders(90%)
     * @apiDescription 查询阿姨订单（谢奕）
     * @apiName actionWorkerOrders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间   时间戳   如 *'1443695400'
     * @apiParam {String} [to] 结束时间     时间戳   如 *'1443695400'
     * @apiParam {String} [oc.customer_id]客户id
     * @apiParam {String} [not_with_work] 0,1
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *      "code": "1",
     *      "msg": "操作成功",
     *      "ret": {
     *      "limit": "1",
     *      "page_total": 4,
     *      "offset": 0,
     *      "orders": [
     *      {
     *          "id": "2",
     *          "order_code": "339710",
     *          "order_parent_id": "0",
     *          "order_is_parent": "0",
     *          "created_at": "1445347126",
     *          "updated_at": "1445347126",
     *          "isdel": "0",
     *          "ver": "3",
     *          "version": "3",
     *          "order_ip": "58.135.77.96",
     *          "order_service_type_id": "1",
     *          "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *          "order_src_id": "1",
     *          "order_src_name": "BOSS",
     *          "channel_id": "20",
     *          "order_channel_name": "后台下单",
     *          "order_unit_money": "20.00",
     *          "order_money": "40.00",
     *          "order_booked_count": "120",
     *          "order_booked_begin_time": "1446249600",
     *          "order_booked_end_time": "1446256800",
     *          "address_id": "397",
     *          "district_id": "3",
     *          "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *          "order_booked_worker_id": "0",
     *          "checking_id": "0",
     *          "order_cs_memo": "",
     *          "order_id": "2",
     *          "order_before_status_dict_id": "2",
     *          "order_before_status_name": "已支付",
     *          "order_status_dict_id": "3",
     *          "order_status_name": "已开始智能指派"
     *      }
     *       ]
     *     "alertMsg": "操作成功"
     *  }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerOrders()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }
        $channels = null;
        if (isset($args['channels'])) {
            $channels = explode(".", $args['channels']);
        }
        @$isAsc = $args['is_asc'];
        if (is_null($isAsc)) {
            $isAsc = true;
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $page = 1;
        if (isset($args['page'])) {
            $page = $args['page'];
        }
        $offset = ($page - 1) * $limit;
        @$from = $args['from'];
        @$to = $args['to'];
        $not_with_work = null;
        if (isset($args['not_with_work'])) {
            $not_with_work = $args['not_with_work'];
        } else {
            $args["owr.worker_id"] = $worker->id;
        }

        try {
            $orderSearch = new OrderSearch();
            $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $orderStatus, $channels, $from, $to, $not_with_work);
            $orders = $orderSearch->searchWorkerOrdersWithStatus($args, $isAsc, $offset, $limit, $orderStatus, $channels, $from, $to, 'order.created_at', $not_with_work);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderGetWorkerOrderFaile);
        }
        $ret = [];
        $ret['limit'] = $limit;
        $ret['page_total'] = ceil($count / $limit);
        $ret['page'] = $page;
        $ret['orders'] = $orders;
        $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderGetWorkerOrderSuccess);
    }

    /**
     * @api {GET} /order/worker-service-orders [GET] /order/worker-service-orders(90%)
     * @apiDescription 查询待服务阿姨订单(谢奕)
     * @apiName actionWorkerServiceOrders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间  时间戳   如 *'1443695400'
     * @apiParam {String} [to] 结束时间  时间戳   如 *'1443695400'
     * @apiParam {String} [oc.customer_id]客户id
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *   "code": "1",
     *   "msg": "操作成功",
     *   "ret": {
     *      "limit": "1",
     *      "page_total": 4,
     *      "offset": 0,
     *      "orders": [
     *      {
     *          "id": "2",
     *          "order_code": "339710",
     *          "order_parent_id": "0",
     *          "order_is_parent": "0",
     *          "created_at": "1445347126",#开始时间
     *          "updated_at": "1445347126",#结束时间
     *          "isdel": "0",
     *          "ver": "3",
     *          "version": "3",
     *          "order_ip": "58.135.77.96",
     *          "order_service_type_id": "1",
     *          "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *          "order_src_id": "1",
     *          "order_src_name": "BOSS",
     *          "channel_id": "20",
     *          "order_channel_name": "后台下单",
     *          "order_unit_money": "20.00",
     *          "order_money": "40.00",
     *          "order_booked_count": "120",
     *          "order_booked_begin_time": "1446249600",
     *          "order_booked_end_time": "1446256800",
     *          "address_id": "397",
     *          "district_id": "3",
     *          "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *          "order_booked_worker_id": "0",
     *          "checking_id": "0",
     *          "order_cs_memo": "",
     *          "order_id": "2",
     *          "order_before_status_dict_id": "2",
     *          "order_before_status_name": "已支付",
     *          "order_status_dict_id": "3",
     *          "order_status_name": "已开始智能指派"
     *        }
     *       ]
     *      }
     *  "alertMsg": "操作成功"
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerServiceOrders()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }

        @$isAsc = $args['is_asc'];
        if (is_null($isAsc)) {
            $isAsc = true;
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $page = 1;
        if (isset($args['page'])) {
            $page = $args['page'];
        }
        $offset = ($page - 1) * $limit;
        @$from = $args['from'];
        @$to = $args['to'];
        $arr = array();
        $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;
        $args["owr.worker_id"] = $worker->id;
        if ($limit <= 0) {
            $limit = 1;
        }
        try {
            $orderSearch = new OrderSearch();
            $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr, null, $from, $to);
            $orders = $orderSearch->searchWorkerOrdersWithStatus($args, $isAsc, $offset, $limit, $arr);
        } catch (Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderGetWorkerServiceOrderFaile);
        }
        $ret = [];
        $ret['limit'] = $limit;
        $ret['page_total'] = ceil($count / $limit);
        $ret['page'] = $page;
        $ret['orders'] = $orders;
        $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderGetWorkerServiceOrderSuccess);
    }

    /**
     * @api {GET} /order/worker-orders-count [GET] /order/worker-orders-count(90%)
     * @apiDescription 查询阿姨订单订单数量 (谢奕)
     *
     * @apiName actionWorkerOrdersCount
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [oc.customer_id]客户id
     * @apiParam {String} [from] 开始时间  时间戳   如 *'1443695400'
     * @apiParam {String} [to] 结束时间    时间戳   如 *'1443695400'
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *    "code": "1",
     *    "msg": "操作成功",
     *    "ret": {
     *    },
     *    "alertMsg": "操作成功"
     *    }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }
        $channels = null;
        if (isset($args['channels'])) {
            $channels = explode(".", $args['channels']);
        }
        @$from = $args['from'];
        @$to = $args['to'];
        $args["owr.worker_id"] = $worker->id;
        $orderSearch = new OrderSearch();
        $ret = [];
        if (!empty($orderStatus)) {
            foreach ($orderStatus as $statuStr) {
                $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, array($statuStr), $channels);
                $ret[$statuStr] = $count;
            }
        } else {
            $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $orderStatus, $channels);
            $ret['count'] = $count;
        }
        $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} /order/worker-service-orders-count [GET]/order/worker-service-orders-count (100%)
     *
     * @apiDescription 查询阿姨待服务订单订单数量 （谢奕）
     * @apiName actionWorkerServiceOrdersCount
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [id] 订单id
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *    "code": "1",
     *    "msg": "操作成功",
     *    "ret": {
     *    }
     *    "alertMsg": "操作成功"
     *
     *    }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerServiceOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        $args["owr.worker_id"] = $worker->id;
        $orderSearch = new OrderSearch();
        $ret = [];
        $arr = array();
        $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;
        $arr[] = OrderStatusDict::ORDER_SERVICE_START;

        $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr);
        $ret['count'] = $count;

        $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} /order/worker-done-orders-history [GET]/order/worker-done-orders-history (90%)
     *
     * @apiDescription 查询阿姨三个月的完成历史订单 (谢奕)
     * @apiName actionWorkerDoneOrdersHistory
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [page] 第几页 从第一页开始
     * @apiParam {String} [limit] 每页包含订单数
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *      "code": "1",
     *      "msg": "操作成功",
     *      "ret": {
     *          "limit": "1",
     *          "page_total": 4,
     *          "offset": 0,
     *          "orders": [
     *          {
     *              "id": "2",
     *               "order_code": "339710",
     *              "order_parent_id": "0",
     *              "order_is_parent": "0",
     *              "created_at": "1445347126",
     *              "updated_at": "1445347126",
     *              "isdel": "0",
     *              "ver": "3",
     *              "version": "3",
     *              "order_ip": "58.135.77.96",
     *              "order_service_type_id": "1",
     *              "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *              "order_src_id": "1",
     *              "order_src_name": "BOSS",
     *              "channel_id": "20",
     *              "order_channel_name": "后台下单",
     *              "order_unit_money": "20.00",
     *              "order_money": "40.00",
     *              "order_booked_count": "120",
     *              "order_booked_begin_time": "1446249600",
     *              "order_booked_end_time": "1446256800",
     *              "address_id": "397",
     *              "district_id": "3",
     *              "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *              "order_booked_worker_id": "0",
     *              "checking_id": "0",
     *              "order_cs_memo": "",
     *              "order_id": "2",
     *              "order_before_status_dict_id": "2",
     *              "order_before_status_name": "已支付",
     *              "order_status_dict_id": "3",
     *              "order_status_name": "已开始智能指派"
     *          }
     *      ]
     *     "alertMsg": "操作成功"
     *  }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerDoneOrdersHistory()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];

        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $beginTime = strtotime('-3 month');
        $endTime = time();

        @$limit = $args["limit"];
        if (is_null($limit)) {
            $limit = 10;
        }
        @$page = $args["page"];
        if (is_null($page)) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $ret['limit'] = $limit;
        $ret['offset'] = $offset;
        $ret['page_total'] = ceil(OrderSearch::getWorkerAndOrderAndDoneTimeCount($worker->id, $beginTime, $endTime) / $limit);
        $ret['orders'] = OrderSearch::getWorkerAndOrderAndDoneTime($worker->id, $beginTime, $endTime, $limit, $offset);
        return $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderWorkerDoneOrderHistorySuccess);
    }

    /**
     * @api {GET} /order/worker-cancel-orders-history [GET]/order/worker-cancel-orders-history(90%)
     *
     * @apiDescription 查询阿姨三个月的取消历史订单（谢奕）
     * @apiName WorkerCancelOrdersHistory
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [page] 第几页 从第一页开始
     * @apiParam {String} [limit] 每页包含订单数
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *    "code": "1",
     *    "msg": "操作成功",
     *    "ret": {
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *      {
     *          "id": "2",
     *          "order_code": "339710",
     *          "order_parent_id": "0",
     *          "order_is_parent": "0",
     *          "created_at": "1445347126",
     *          "updated_at": "1445347126",
     *          "isdel": "0",
     *          "ver": "3",
     *          "version": "3",
     *          "order_ip": "58.135.77.96",
     *          "order_service_type_id": "1",
     *          "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *          "order_src_id": "1",
     *          "order_src_name": "BOSS",
     *          "channel_id": "20",
     *          "order_channel_name": "后台下单",
     *          "order_unit_money": "20.00",
     *          "order_money": "40.00",
     *          "order_booked_count": "120",
     *          "order_booked_begin_time": "1446249600",
     *          "order_booked_end_time": "1446256800",
     *          "address_id": "397",
     *          "district_id": "3",
     *          "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *          "order_booked_worker_id": "0",
     *          "checking_id": "0",
     *          "order_cs_memo": "",
     *          "order_id": "2",
     *          "order_before_status_dict_id": "2",
     *          "order_before_status_name": "已支付",
     *          "order_status_dict_id": "3",
     *          "order_status_name": "已开始智能指派"
     *      }
     *    ],
     *   "alertMsg": "操作成功"
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionWorkerCancelOrdersHistory()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $beginTime = strtotime('-3 month');
        $endTime = time();

        @$limit = $args["limit"];
        if (is_null($limit)) {
            $limit = 10;
        }
        @$page = $args["page"];
        if (is_null($page)) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $ret['limit'] = $limit;
        $ret['offset'] = $offset;
        $ret['page_total'] = ceil(OrderSearch::getWorkerAndOrderAndCancelTimeCount($worker->id, $beginTime, $endTime) / $limit);
        $ret['orders'] = OrderSearch::getWorkerAndOrderAndCancelTime($worker->id, $beginTime, $endTime, $limit, $offset);
        return $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderWorkerCancelOrderHistorySuccess);
    }

    /**
     * @api {GET} /order/status-orders-count [GET]/order/status-orders-count(70%)
     * @apiDescription 查询用户不同状态订单数量(谢奕)
     * @apiName StatusOrdersCount
     * @apiGroup Order
     * 
     * @apiDescription 获得各种状态的订单数量
     * 
     * @apiParam {String} access_token 订单状态
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "操作成功",
     *      "alertMsg": "获取状态订单数量成功"
     *      "ret": {
     *          "1": "9",
     *          "2": "0",
     *          "3": "4",
     *          "4": "0",
     *          "5": "0",
     *          "6": "1",
     *          "7": "0",
     *          "8": "0",
     *          "9": "0",
     *          "10": "0",
     *          "11": "0",
     *          "12": "0"
     *          }
     *     }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionStatusOrdersCount()
    {

        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $orderStatus = null;
        if (isset($args['order_status'])) {
            $orderStatus = explode(".", $args['order_status']);
        }
        $channels = null;
        if (isset($args['channels'])) {
            $channels = explode(".", $args['channels']);
        }
        @$from = $args['from'];
        @$to = $args['to'];
        $args["oc.customer_id"] = $user->id;
        $args['order_parent_id'] = 0;
        $orderSearch = new OrderSearch();

        $ret = [];
        if (!empty($orderStatus)) {
            foreach ($orderStatus as $statuStr) {
                $count = $orderSearch->searchOrdersWithStatusCount($args, array($statuStr), $channels);
                $ret[$statuStr] = $count;
            }
        } else {
            $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus, $channels);
            $ret['count'] = $count;
        }

        $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::orderGetStatusOrdersCountSuccess);
    }

    /**
     * @api {GET} /order/order-status-history [GET] /order/order-status-history(70%)
     * @apiDescription 查询用户某个订单状态历史状态记录(谢奕/田玉星)
     *
     * @apiName actionOrderStatusHistory
     * @apiGroup Order
     *
     * @apiParam {String} order_id 订单id
     * @apiParam {String} access_token 认证令牌
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiSuccess {Object[]} orders 订单信息.
     * @apiSuccess {Object[]} status_history 该状态订单.
     *
     * @apiSuccessExample Success-Response:

     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "查询订单状态记录成功"
     *   "ret": {
     *       "orders": {
     *           "order_booked_begin_time": "预约开始时间",
     *           "order_booked_end_time": "预约结束时间",
     *           "order_address": "服务地址",
     *           "order_booked_worker_id": "服务阿姨ID",
     *           "order_booked_worker_name": "服务阿姨姓名",
     *           "order_status_customer":"订单当前状态",
     *           "order_service_type_name":"订单服务类别",
     *           "order_code": "订单号",
     *           "order_money": "订单金额",
     *           "order_channel_name": "下单渠道",
     *           "order_pay_type": "支付方式",
     *           "order_pay_channel_name": "支付渠道名称",
     *           "order_pay_money": "订单支付金额",
     *           "order_use_acc_balance": "使用余额",
     *           "order_use_card_money": "使用服务卡金额",
     *           "order_use_coupon_money": "使用优惠卷金额",
     *           "order_use_promotion_money": "使用促销金额"
     *       },
     *       "status_history": [
     *           {
     *               "created_at": "状态更新时间",
     *               "order_status_customer": "当前状态"
     *           },
     *           {
     *               "created_at": "状态更新时间",
     *               "order_status_customer": "当前状态"
     *           }
     *       ]
     *   }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionOrderStatusHistory()
    {
        $args = Yii::$app->request->get() or $args = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($args['access_token']) || !$args['access_token'] || !CustomerAccessToken::getCustomer($args['access_token'])) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        //判断订单号
        if (!isset($args['order_id']) || !is_numeric($args['order_id'])) {
            return $this->send(null, "该订单不存在", 0, 200, null, alertMsgEnum::orderExistFaile);
        }
        $orderWhere = array("id" => $args['order_id'], 'order_parent_id' => 0);
        $orderInfo = (new OrderSearch())->searchOrdersWithStatus($orderWhere);
        if (!$orderInfo) {
            return $this->send(null, "该订单不存在", 0, 200, null, alertMsgEnum::orderExistFaile);
        }
        $orderInfo = $orderInfo[0];
        //订单数据整理
        $workerName = "";
        if ($orderInfo['order_booked_worker_id']) {
            $workerInfo = Worker::getWorkerInfo($orderInfo['order_booked_worker_id']);
            $workerName = $workerInfo['worker_name'];
        }
        $ret['orders'] = [
            //家庭保洁
            'order_booked_begin_time' => $orderInfo['order_booked_begin_time'],
            'order_booked_end_time' => $orderInfo['order_booked_end_time'],
            'order_address' => $orderInfo['order_address'],
            'order_booked_worker_id' => $orderInfo['order_booked_worker_id'],
            'order_booked_worker_name' => $workerName,
            'order_status_customer' => $orderInfo['order_status_customer'],
            //订单信息
            'order_service_type_name' => $orderInfo['order_service_type_name'],
            'order_code' => $orderInfo['order_code'],
            'order_money' => $orderInfo['order_money'],
            'order_channel_name' => $orderInfo['order_channel_name'],
            'order_pay_type' => $orderInfo['pay_channel_id'],
            'order_pay_channel_name' => $orderInfo['order_pay_channel_name'],
            'order_pay_money' => $orderInfo['order_pay_money'],
            'order_use_acc_balance' => $orderInfo['order_use_acc_balance'],
            'order_use_card_money' => $orderInfo['order_use_card_money'],
            'order_use_coupon_money' => $orderInfo['order_use_coupon_money'],
            'order_use_promotion_money' => $orderInfo['order_use_promotion_money']
        ];

        $status_history = OrderStatusHistory::findByOrderId($args['order_id']);

        foreach ($status_history as $key => $val) {
            $ret['status_history'][$key]['created_at'] = $val['created_at'];
            $ret['status_history'][$key]['order_status_customer'] = $val['order_status_customer'];
        }
        $this->send($ret, "操作成功", 1, 200, NULL, alertMsgEnum::orderGetOrderStatusHistorySuccess);
    }

    /**
     * @api {PUT} /order/cancel-order [PUT] /order/cancel-order(100% )
     * @apiDescription 取消订单(郝建设)
     * @apiName actionCancelOrder
     * @apiGroup Order
     *
     * @apiParam {String}   access_token            用户认证
     * @apiParam {String}   order_channel_name      订单渠道名称
     * @apiParam {String}   [order_cancel_reason]   取消原因
     * @apiParam {String}   [order_code]            订单编号   
     * @apiParam {String}   [order_batch_code]      周期订单号  
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "693345订单取消成功",
     *       "alertMsg": "订单取消成功"
     *       "ret":{
     *         1
     *       }
     *     }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionCancelOrder()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (!CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {

            $reason = isset($param['order_cancel_reason']) ? $param['order_cancel_reason'] : "";
            $order_cancel_reason = array('临时有事，改约', '信息填写有误，重新下单', '不需要服务了');

            if (!in_array($reason, $order_cancel_reason)) {
                $reason = '其他原因#' . $reason;
            }

            if (!isset($param['order_code']) && !isset($param['order_batch_code'])) {
                return $this->send(null, "缺少必要参数:订单编号或者周期订单号", 0, 200, null, '缺少必要参数:订单编号或者周期订单号');
            }

            if (empty($param['order_code'])) {
                $param['order_code'] = $param['order_batch_code'];
            }

            try {
                $result = Order::cancelByOrderCode($param['order_code'], Order::ADMIN_CUSTOMER, OrderOtherDict::NAME_CANCEL_ORDER_CUSTOMER_OTHER_CAUSE, $reason);

                if ($result['status']) {
                    return $this->send([$result['status']], $param['order_code'] . "订单取消成功", 1, 200, null, alertMsgEnum::orderCancelSuccess);
                } else {
                    return $this->send(null, $result['msg'], 0, 200, null, alertMsgEnum::orderCancelFaile . '[' . $result['error_code'] . ']');
                }
            } catch (Exception $e) {
                return $this->send(null, $param['order_code'] . "订单取消异常:" . $e, 1024, 200, null, alertMsgEnum::orderCancelFaile . '[' . $result['error_code'] . ']');
            }
        } else {
            return $this->send(null, "核实用户订单唯一性失败，用户id：" . $customer->id . ",订单id：" . $param['order_code'], 0, 200, NULL, alertMsgEnum::orderCancelFaile);
        }
    }

    /**
     * @api {DELETE} /order/hiden-order [DELETE]/order/hiden-order（ 100%）
     *
     * @apiDescription 删除订单(郝建设)
     * @apiName actionHidenOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token    用户认证
     * @apiParam {String} order_channel_name    订单渠道名称
     * @apiParam {int}    [order_code]            订单号  
     * @apiParam {int}    [order_batch_code]    周期订单号   
     * @apiDescription  客户端删除订单，后台软删除 隐藏订单
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "订单删除成功",
     *       "alertMsg": "订单删除成功"
     *        "ret":{}
     *     }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionHidenOrder()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {

            if (!isset($param['order_code']) && !isset($param['order_batch_code'])) {
                return $this->send(null, "缺少必要参数:订单编号或者周期订单号", 0, 200, null, '缺少必要参数:订单编号或者周期订单号');
            }

            if (empty($param['order_code'])) {
                $param['order_code'] = $param['order_batch_code'];
            }

            try {
                if (Order::customerDel($param['order_code'], 0)) {
                    return $this->send(null, "删除订单成功", 1, 200, null, alertMsgEnum::orderDeleteSuccess);
                } else {
                    return $this->send(null, "订单删除失败", 0, 200, null, alertMsgEnum::orderDeleteFaile);
                }
            } catch (\Exception $e) {
                return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderDeleteFaile);
            }
        }
    }

    /**
     * @api {GET} /order/worker-history-orders [GET]/order/worker-history-orders (0%)
     * @apiName actionWorkerHistoryOrders
     * @apiGroup Order
     * 
     * @apiDescription 阿姨全部订单月份列表 (赵顺利)
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} access_token    会话id.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "year": "2015",
     *          "firstYear": "2015",
     *          "lastYear": "2015",
     *          "info":
     *          [
     *          {
     *              "month": "09",
     *              "order_num": "8",
     *              "work_hour": "23.5"
     *          }
     *          ],
     *          "msgStyle": ""
     *      },
     *      "alertMsg": "操作成功"
     * }
     *
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionWorkerHistoryOrders()
    {
        
    }

    /**
     * @api {GET} /order/get-worker-orders [GET]/order/get-worker-orders(100%)
     * @apiName actionGetWorkerOrders
     * @apiGroup Order
     * @apiDescription 阿姨抢单数 (郝建设)
     * @apiParam {String} access_token      阿姨认证
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} [page_size]         条数  #leveltype =2 时要传递
     * @apiParam {String} [page]              页面  #leveltype =2 时要传递
     * @apiParam {String} leveltype          判断标示 leveltype=1 指定阿姨订单数，待抢单订单订单数;  leveltype=2 指定阿姨订单列表，待抢单订单列表,指定阿姨订单数，待抢单订单订单数
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *  指定阿姨订单数/待抢单订单订单数 leveltype=1
     * {
     *      "code": "1",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "workerData": "指定阿姨订单数",
     *          "orderData": "待抢单订单数",
     *          "workerServiceCount": "待服务订单数",
     *          "worker_is_block": 0/1  阿姨是否封号 0正常1封号
     *      },
     *      "alertMsg": "操作成功"
     * }
     *
     *   * 指定阿姨订单列表/待抢单订单列表 leveltype=2
     * {
     * "code": "1",
     * "msg":"操作成功",
     * "ret":
     *     {
     *   "orderData": [  //指定阿姨订单列表 待抢单订单列表
     * 	    {
     *       "order_id": "订单号",
     *       "order_code": "订单编号",
     *       "batch_code": "周期订单号",
     *       "booked_begin_time": "服务开始时间",
     *       "booked_end_time": "服务结束时间",
     *       "channel_name": "服务类型名称",
     *       "booked_count": "时常",
     *       "address": "服务地址",
     *       "need": "备注说明",
     *       "money": "订单价格",
     *        "lng"     经度,
     *         "lat"    纬度,
     *      ### "is_booker_worker" => "判断标示 1有时间格式 0没有时间格式", # 11月六号 涛涛说不要这个时间标示 18:22
     *       ##"times" => '2:00:00', # 11月六号 涛涛说不要这个时间标示 18:22
     *                    "order_time":
     *                 [
     *                    '开始时间 - 结束时间',
     *                    '1447133400 - 1447151400',
     *                   '1447738200 - 1447756200'
     *               ]
     *          },
     * 	       ]
     *       },
     *    "pageNum":"总页码数"
     *    },
     * "alertMsg": "操作成功"
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionGetWorkerOrders()
    {
        $param = Yii::$app->request->get();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        #判断传递的参数
        if (empty($param['leveltype'])) {
            return $this->send(null, "缺少规定的参数leveltype", 0, 200, null, alertMsgEnum::userLoginFailed);
        }

        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            if ($param['leveltype'] == 2) {
                if (empty($param['page_size']) || empty($param['page'])) {
                    return $this->send(null, "缺少规定的参数,page_size或page不能为空", 0, 200, null, alertMsgEnum::userLoginFailed);
                }
                try {
                    #指定阿姨订单列表 待抢单订单列表
                    $workerCount = OrderSearch::getPushWorkerOrders($worker->id, $param['page_size'], $param['page']);
//                    foreach ($workerCount as $key => $val) {
//                        if (@$val['is_booker_worker']) {
//                            $workerCount[$key]['times'] = '2:00:00';
//                        } else {
//                            $workerCount[$key]['times'] = '';
//                        }
//                    }
                    #指定阿姨订单数
                    $workerOrderCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 1);
                    #待抢单订单数
                    $orderData = OrderSearch::getPushWorkerOrdersCount($worker->id, 0);
                    $pageNumber = ceil(($workerOrderCount + $orderData) / $param['page_size']);
                    $ret['pageNum'] = $pageNumber;
                    $ret["orderData"] = $workerCount; // $workerCount; 实际返回数组名称
                    return $this->send($ret, $this->workerText[$param['leveltype']], 1, 200, null, alertMsgEnum::worerSuccess);
                } catch (\Exception $e) {
                    return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::userLoginFailed);
                }
            } else if ($param['leveltype'] == 1) {
                try {

                    #指定阿姨订单数
                    $workerCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 1);
                    #待抢单订单数
                    $workerCountTwo = OrderSearch::getPushWorkerOrdersCount($worker->id, 0);
                    $args["owr.worker_id"] = $worker->id;
                    $args["oc.customer_id"] = null;
                    $orderSearch = new OrderSearch();
                    $ret = [];
                    $arr = array();
                    $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
                    $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
                    $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;
                    $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr);
                    #待服务订单数
                    $ret['workerServiceCount'] = $count;
                    #指定阿姨订单数
                    $ret['workerData'] = $workerCount;
                    #待服务订单数
                    $ret['orderData'] = $workerCountTwo;
                    #阿姨状态
//                    $ret['worker_is_block'] = [
//                        $worker->worker_is_block
//                    ]; 状态有后台传递
                    $ret['worker_is_block'] = $worker->worker_is_block;
                    return $this->send($ret, $this->workerText[$param['leveltype']], 1, 200, null, alertMsgEnum::taskDoingSuccess);
                } catch (\Exception $e) {
                    return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::userLoginFailed);
                }
            } else {
                return $this->send(null, "leveltype指定参数错误,不能大于2", 0, 200, null, alertMsgEnum::levelType);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {POST} /order/create-recursive-orderes [POST] /order/create-recursive-orderes（100%）
     *
     * @apiName actionCreateRecursiveOrderes
     * @apiGroup Order
     * @apiDescription 创建周期订单(郝建设)
     *
     * @apiParam  {String}  access_token      会话id. 必填
     * @apiParam {String}   order_channel_name      订单渠道名称
     * @apiParam  {integer} order_service_item_id 服务类型 商品id 必填
     * @apiParam  {int}     address_id 客户地址id 必填
     * @apiParam  {string}  order_customer_phone 客户手机号 必填
     * @apiParam  {int}     order_is_use_balance 是否使用余额 0否 1是 必填
     * @apiParam  {int}     order_booked_count 服务时长
     * @apiParam  {int}     [pay_channel_key]      支付渠道
     * @apiParam  {string}  [order_booked_worker_id] 指定阿姨id
     * @apiParam  {int}     [accept_other_aunt] 0不接受 1接受
     * @apiParam  {string}  [order_customer_need] 客户需求
     * @apiParam  {string}  [order_customer_memo] 客户备注
     * @apiParam  {int}    [coupon_id] 优惠券id
     * @apiParam  {Object} [order_booked_time] 
     * @apiParam  {string} [order_booked_time.order_booked_begin_time] 开始时间 时间戳 如："14012312312321"
     * @apiParam  {string} [order_booked_time.order_booked_end_time]   结束时间 时间戳 如："14012312312321"
     * @apiParam  {int}    [order_booked_time.coupon_id]      优惠券
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
     *      "msg":"创建周期订单成功", 
     *      "ret": {},
     *      "alertMsg": "创建周期订单成功"
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *    "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     *
     */
    public function actionCreateRecursiveOrderes()
    {
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        #获取用户ip
        $order_ip = Yii::$app->getRequest()->getUserIP();

        #判断服务类型
        if (empty($param['order_service_item_id'])) {
            return $this->send(null, "服务类型不能为空", 0, 200, null, alertMsgEnum::orderServiceItemIdFaile);
        }
        #判断订单来源
//        if (empty($param['order_src_id'])) {
//            return $this->send(null, "订单来源id不能为空", 0, 200, null, alertMsgEnum::orderSrcIdFaile);
//        }
        #判断地址不能为空
        if (empty($param['address_id'])) {
            return $this->send(null, "用户地址不能为空", 0, 200, null, alertMsgEnum::orderAddressFaile);
        }
        #判断客户手机不能为空
        if (empty($param['order_customer_phone'])) {
            return $this->send(null, "客户手机不能为空", 0, 200, null, alertMsgEnum::orderCustomerPhoneFaile);
        }
        #支付渠道
        $attributes['pay_channel_key'] = isset($param['pay_channel_key']) ? $param['pay_channel_key'] : "";

        #判断是否使用余额
        if (empty($param['order_is_use_balance'])) {
            return $this->send(null, "使用余额不能为空", 0, 200, NULL, alertMsgEnum::orderIsUseBalanceFaile);
        }
        if (is_null($param['accept_other_aunt'])) {
            $param['accept_other_aunt'] = 0;
        }

        if (empty($param['order_customer_memo'])) {
            $param['order_customer_memo'] = '';
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            $attributes = array(
                "order_ip" => $order_ip,
                "order_service_item_id" => $param['order_service_item_id'],
                "order_channel_name" => isset($param['order_channel_name']) ? $param['order_channel_name'] : "",
                "address_id" => $param['address_id'],
                "customer_id" => $customer->id,
                "order_customer_phone" => $param['order_customer_phone'],
                "admin_id" => Order::ADMIN_CUSTOMER,
                "pay_channel_key" => $param['pay_channel_key'],
                "order_is_use_balance" => $param['order_is_use_balance'],
                //order_booked_worker_id edit by tianyuxing
                "order_booked_worker_id" => isset($param['order_booked_worker_id']) ? intval($param['order_booked_worker_id']) : 0,
                "order_customer_need" => isset($param['order_customer_need']) ? $param['order_customer_need'] : "",
                "order_customer_memo" => isset($param['order_customer_memo']) ? $param['order_customer_memo'] : "",
                "order_flag_change_booked_worker" => $param['accept_other_aunt']
            );

            #开始时间 结束时间 时间段 优惠码
            $booked_list = array();
            foreach ($param['order_booked_time'] as $key => $val) {
                if (!isset($param['order_booked_time']['coupon_id'])) {
                    $val['coupon_id'] = null;
                }
                $booked_list[] = [
                    'order_booked_begin_time' => strtotime($val['order_booked_begin_time']),
                    'order_booked_end_time' => strtotime($val['order_booked_end_time']),
                    'order_booked_count' => $val['order_booked_count'],
                    'coupon_id' => $val['coupon_id']
                ];
            }

            try {
                $order = new Order();
                $createOrder = $order->createNewBatch($attributes, $booked_list);

                if ($createOrder['status'] == 1) {
                    return $this->send($createOrder['batch_code'], "添加成功", 1, 200, null, alertMsgEnum::orderCreateRecursiveOrderSuccess);
                } else {
                    return $this->send(null, "创建周期订单失败！错误编号：[{$createOrder['error_code']}]", 0, 200, null, alertMsgEnum::orderCreateRecursiveOrderFaile);
                }
            } catch (\Exception $e) {
                return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderCreateRecursiveOrderFaile);
            }
        } else {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {PUT} /order/set-worker-order [PUT]/order/set-worker-order (100%)
     *
     * @apiName actionSetWorkerOrder
     * @apiGroup Order
     * @apiDescription 阿姨抢单提交 （郝建设 ）
     *
     * @apiParam {String} access_token        阿姨认证
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {int}    order_id            订单号
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
     *      "msg":"阿姨抢单提交成功",
     *      "alertMsg": "阿姨抢单提交成功",
     *      "ret":{
     *     }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *     "code": 401,
     *     "msg": "用户无效,请先登录",
     *     "ret": {},
     *     "alertMsg": "用户认证已经过期,请重新登录"
     *  }
     * 
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 200 OK
     *  {
     *     "code": 0,
     *     "msg": "阿姨抢单提交失败",
     *     "ret": {
     *     "id": [
     *       "阿姨服务时间冲突！" 或  "订单正在进行人工指派！"  或  "订单已经指派阿姨！"
     *      ]
     *     },
     *     "alertMsg": "阿姨服务时间冲突！" 或  "订单正在进行人工指派！"  或  "订单已经指派阿姨！"
     *  }
     *
     */
    public function actionSetWorkerOrder()
    {
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !WorkerAccessToken::getWorker($param['access_token'])) {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        #订单号不能为空
        if (empty($param['order_id'])) {
            return $this->send(null, "数据不完整,请输入订单号", 0, 200, null, alertMsgEnum::orderGetOrderNumber);
        }


        $worker = WorkerAccessToken::getWorker($param['access_token']);

        if (!empty($worker) && !empty($worker->id)) {
            try {
                $setWorker = Order::sysAssignDone($param['order_id'], $worker->id);

                if (!empty($setWorker['errors'])) {
                    foreach ($setWorker['errors'] as $key => $val) {
                        $values = $val[0];
                    }
                }

                if ($setWorker && empty($setWorker["errors"])) {
                    return $this->send($setWorker, "阿姨抢单提交成功", 1, 200, null, alertMsgEnum::orderSetWorkerOrderSuccess);
                } else {
                    return $this->send($setWorker["errors"], "阿姨抢单提交失败", 0, 200, null, $values);
                }
            } catch (\Exception $e) {
                return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderSetWorkerOrderFaile);
            }
        } else {
            return $this->send(null, "用户无效,请先登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /order/get-customer-recursive-order [GET]/order/get-customer-recursive-order(100%）
     *
     * @apiDescription 获取周期订单 （郝建设）
     * @apiName actionGetCustomerRecursiveOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token    用户认证
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} order_batch_code 周期订单号
     * @apiParam {int}    workerType    获取周期订单传递的表示 workerType=1; 不适用改字段 workerType=0; 
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK  workerType 为空
     *    {
     *     "code": 1,
     *     "msg": "操作成功",
     *     "ret": [
     *     {
     *     "id": "8",
     *     "order_code": "订单号",
     *     "order_batch_code": "周期订单号",
     *     "order_parent_id": "0",
     *     "order_is_parent": "0",
     *     "sub_order": {
     *     "1": {
     *     "id": "9",
     *     "order_code": "订单号",
     *     "order_batch_code": "周期订单号",
     *     "order_parent_id": "1",
     *     "order_sys_memo": ""
     *     },
     *     "2": {
     *     "id": "10",
     *     "order_code": "订单号",
     *     "order_batch_code": "周期订单号",
     *     "order_cs_memo": "",
     *     "order_sys_memo": ""
     *    }
     *    }
     *    }
     *     ],
     *     "alertMsg": "操作成功"
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": 401,
     *       "msg": "用户认证已经过期,请重新登录"
     *       "ret":{},
     *       "alertMsg": "用户认证已经过期,请重新登录"
     *
     *     }
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK  workerType=1 有值，提供江江用
     * {
     *    "code": 1,
     *    "msg": "操作成功",
     *    "ret": {
     *        "worker_money": "阿姨支付现金",
     *        "order_money":  "周期订单总价格",
     *        "order_channel_name": "订单渠道名称",
     *        "order_service_type_name": "订单服务类别",
     *        "order_service_item_name": "订单服务项",
     *        "order_cs_memo": "客服备注",
     *        "order_lat": "纬度",
     *        "order_lng": "经度",
     *        "order_address": "详细地址",
     * 	     "order_batch_code": "周期订单号"
     *        "times": [
     *            {
     *                "order_booked_begin_time": "开始时间时间戳",
     *                "order_booked_end_time": "结束时间时间戳",
     *                "long_time": "时长",
     *                "id": "33",
     *                "order_code": "241511106213910",
     *            },
     *            {
     *                "order_booked_begin_time": "1448334000",
     *                "order_booked_end_time": "1448341200",
     *                "long_time": 2,
     *                "id": "34",
     *                "order_code": "051511106213998",
     *            },
     *        ]
     *    },
     *    "alertMsg": "操作成功"
     * }
     *
     */
    public function actionGetCustomerRecursiveOrder()
    {
        $param = Yii::$app->request->get();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        if (empty($param['order_batch_code'])) {
            return $this->send(null, "数据不完整,请输入周期订单号", 0, 200, null, alertMsgEnum::orderGetOrderWorkerNumber);
        }

        try {
            $orderSearch = new OrderSearch();
            $order = $orderSearch->searchOrdersWithStatus(["order_batch_code" => $param['order_batch_code']]);
            if (count($order) > 0) {
                $r_order = [];
                if (!$param['workerType']) {
                    foreach ($order as $key => $val) {
                        if ($val['order_parent_id'] == 0) {
                            $r_order = $val;
                            $index = $key;
                        }
                    }
                    //判断是否批量订单，如果是批量订单，取出当前第一个数组，当主订单
                    if (empty($r_order)) {
                        $r_order = $order[0];
                        $index = 0;
                    }
                    unset($order[$index]);
                    $r_order['sub_order'] = array_merge($order, []);
                } else {
                    foreach ($order as $k => $v) {
                        #是否现金支付
                        if ($v['pay_channel_id'] == 2) {
                            @$r_order['worker_money'] += $v['order_money'];
                        } else {
                            @$r_order['worker_money'] = 0;
                        }
                        if ($v['order_parent_id'] == 0) {
                            $r_order['order_id'] = $v['id'];
                        }
                        @$r_order['order_money'] += $v['order_money'];
                        $r_order['order_channel_name'] = $v['order_channel_name'];
                        $r_order['order_service_type_name'] = $v['order_service_type_name'];
                        $r_order['order_service_item_name'] = $v['order_service_item_name'];
                        $r_order['pay_channel_id'] = $v['pay_channel_id'];
                        $r_order["long_time"] = ($v['order_booked_end_time'] - $v['order_booked_begin_time']) % 86400 / 3600;
                        $r_order['order_cs_memo'] = $v['order_cs_memo'];
                        $r_order['order_lat'] = $v['order_lat'];
                        $r_order['order_lng'] = $v['order_lng'];
                        $r_order['order_address'] = $v['order_address'];
                        $r_order["order_batch_code"] = $v['order_batch_code'];
                        $r_order['times'][$k]["order_booked_begin_time"] = $v['order_booked_begin_time'];
                        $r_order['times'][$k]["order_pay_type"] = $v['pay_channel_id'];
                        $r_order['times'][$k]["order_booked_end_time"] = $v['order_booked_end_time'];
                        $r_order['times'][$k]["long_time"] = ($v['order_booked_end_time'] - $v['order_booked_begin_time']) % 86400 / 3600;
                        $r_order['times'][$k]["id"] = $v['id'];
                        $r_order['times'][$k]["order_code"] = $v['order_code'];
                    }
                }

                return $this->send($r_order, "操作成功", 1, 200, null, alertMsgEnum::checkTaskSuccess);
            } else {
                return $this->send(null, "操作失败", 0, 200, null, alertMsgEnum::orderGetOrderWorkerFaile);
            }
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderGetOrderWorkerFaile);
        }
    }

    /**
     * @api {GET} /order/get-order-one [GET] /order/get-order-one（100%）
     * @apiDescription 获取一个订单的对象 （郝建设） 
     * @apiName actionGetOrderOne
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} id            订单号
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *    "ret": {
     *   "id": "2",
     *    "order_code": "订单号",
     *   "order_batch_code": "周期订单号",
     *   "order_parent_id": "父级id",
     *   "order_is_parent": 有无子订单 1有 0无,
     *   "created_at": "1446041297", 创建时间
     *   "updated_at": "1446041297", 修改时间
     *   "ver": "1",
     *   "version": "1",
     *   "order_ip": "114.242.250.248",
     *    "order_service_type_id": 1,
     *   "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *   "order_src_id": 1,
     *   "order_src_name": "BOSS",
     *   "channel_id": "2",
     *   "order_channel_name": "H5手机微信",
     *   "order_unit_money": "20.00",
     *   "order_money": "40.00",
     *   "order_booked_count": "120",
     *   "order_booked_begin_time": "1445581800",
     *   "order_booked_end_time": "1445589000",
     *   "city_id": "110100",
     *   "district_id": "5",
     *   "address_id": "1",
     *   "order_address": ",北京市,西城区,西城区西什库大街16号123,空,17091005305",
     *  },
     * "alertMsg": "操作成功"
     * }
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": 401,
     *       "msg": "用户认证已经过期,请重新登录，"
     *       "ret":{},
     *       "alertMsg": "操作成功"
     *     }
     *
     */
    public function actionGetOrderOne()
    {
        $param = Yii::$app->request->get();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 200, null, alertMsgEnum::userLoginFailed);
        }

        if (empty($param['id'])) {
            return $this->send(null, "订单号不能为空！", 0, 200, null, alertMsgEnum::orderExistFaile);
        }
        try {
            $order = OrderSearch::getOne($param['id'])->getAttributes();

            if ($order) {
                $ret["orderData"] = $order;
                return $this->send($ret, "操作成功", 1, 200, null, alertMsgEnum::checkTaskSuccess);
            } else {
                return $this->send(null, "操作失败", 0, 200, null, alertMsgEnum::GetOrderOneFail);
            }
        } catch (Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::orderGetOrdersFaile);
        }
    }

    /**
     * 智能派单自动推送访问接口
     *
     * @author  linhongyou
     * @param $id
     * @return array
     */
    public function actionPush($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return OrderPush::push($id);
    }

    /**
     * @api {GET} /order/get-order-channel-list [GET] /order/get-order-channel-list (100%)
     * @apiName actionGetOrderChannelList（郝建设）
     * @apiGroup order
     * @apiDescription 获得订单渠道配置信息
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "数据获取成功",
     *      "ret":
     *      [
     *          {
     *              "order_channel_id": "8",           订单渠道id
     *              "order_channel_name": "美团上门",  订单渠道名称
     *          },
     *          {
     *              "order_channel_id": "9",           订单渠道id
     *              "order_channel_name": "点评到家",  订单渠道名称
     *          },
     *       ],
     *  }
     *
     */
    public function actionGetOrderChannelList()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //pop访问时可以不用输入access_token（用本身的验证加密方法）
        @$apiPopKey = Yii::$app->params["apiPopKey"];
        @$apiSecretKey = Yii::$app->params["apiSecretKey"];
        $sign = isset($param["sign"]) ? $param["sign"] : "";
        $nonce = isset($param["nonce"]) ? $param["nonce"] : "";
        $arrParams = array();
        $arrParams["sign"] = $sign;
        $arrParams["nonce"] = $nonce;
        $arrParams["api_key"] = $apiPopKey;
        try {
            $objSign = new EjjEncryption($apiPopKey, $apiSecretKey);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        try {
            $bolCheck = $objSign->checkSignature($arrParams);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (!$bolCheck) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
        }
        try {
            $orderChannels = OperationOrderChannel::getorderchannellist("all");
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        return $this->send($orderChannels, "数据获取成功", 1, 200, null, alertMsgEnum::getGoodsesSuccess);
    }

}
