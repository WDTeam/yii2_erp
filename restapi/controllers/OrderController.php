<?php

namespace restapi\controllers;

use \core\models\order\OrderPush;
use Faker\Provider\DateTime;
use Yii;
use \core\models\order\Order;
use \core\models\customer\CustomerAccessToken;
use \core\models\customer\CustomerAddress;
use \core\models\order\OrderSearch;
use \core\models\worker\WorkerAccessToken;
use \dbbase\models\order\OrderStatusDict;
use yii\web\Response;

class OrderController extends \restapi\components\Controller
{

    public $workerText = array(1 => '指定阿姨订单数,待抢单订单数', '指定阿姨订单列表', '待抢单订单列表');

    /**
     * @api {POST} /order/create-order 创建订单 (100%xieyi)
     *
     *
     * @apiName ActionCreateOrder
     * @apiGroup Order
     * @apiDescription 创建订单v1
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_service_type_id 服务类型商品id
     * @apiParam {String} order_src_id 订单来源id 访问源(android_4.2.2)
     * @apiParam {String} order_booked_begin_time 服务开始时间
     * @apiParam {String} order_booked_end_time 服务结束时间
     * @apiParam {String} order_customer_phone 用户手机号
     * @apiParam {String} order_pay_type 支付方式 1现金 2线上 3第三方 必填
     * @apiParam {String} address_id 订单地址id
     * @apiParam {String} channel_id 下单渠道
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
     *          "order_service_type_id": "1",
     *          "order_src_id": "2",
     *          "order_booked_begin_time": "1445251619",
     *          "order_booked_end_time": "1445255219",
     *          "address_id": "1",
     *          "channel_id": "20",
     *          "order_ip": "::1",
     *          "order_parent_id": 0,
     *          "order_is_parent": 0,
     *          "order_unit_money": "20.0000",
     *          "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *          "order_booked_count": 60,
     *          "order_money": 20,
     *          "order_address": "光华路soho,张三,18622344432",
     *          "order_code": "1110",
     *          "order_src_name": "IOS",
     *          "order_channel_name": "后台下单",
     *          "checking_id": 0,
     *          "isdel": 0,
     *          "created_at": 1445320069,
     *          "updated_at": 1445320069,
     *          "id": 8
     *      }
     *  }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionCreateOrder()
    {
        $args = Yii::$app->request->post() or
                $args = json_decode(Yii::$app->request->getRawBody(), true);
        $attributes = [];
        @$token = $args['access_token'];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 0);
        }
        $attributes['customer_id'] = $user->id;

        if (is_null($args['order_service_type_id'])) {
            return $this->send(null, "请输入服务类型商品id", 0);
        }
        $attributes['order_service_type_id'] = $args['order_service_type_id'];

        if (is_null($args['order_src_id'])) {
            return $this->send(null, "数据不完整,缺少订单来源", 0);
        }
        $attributes['order_src_id'] = $args['order_src_id'];

        if (is_null($args['order_booked_begin_time'])) {
            return $this->send(null, "数据不完整,请输入初始时间", 0);
        }
        $attributes['order_booked_begin_time'] = $args['order_booked_begin_time'];

        if (is_null($args['order_booked_end_time'])) {
            return $this->send(null, "数据不完整,请输入完成时间", 0);
        }
        $attributes['order_booked_end_time'] = $args['order_booked_end_time'];

        if (is_null($args['order_pay_type'])) {
            return $this->send(null, "数据不完整,请输入支付方式", 0);
        }
        $attributes['order_pay_type'] = $args['order_pay_type'];

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
                return $this->send(null, "地址数据不完整,请输入常用地址id或者城市,地址名（包括区）", 0);
            }
        } else {
            return $this->send(null, "数据不完整,请输入常用地址id或者城市,地址名", 0);
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

        if (isset($args['channel_id'])) {
            $attributes['channel_id'] = $args['channel_id'];
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


        $attributes['admin_id'] = 0;
        $order = new \core\models\order\Order();
        $is_success = $order->createNew($attributes);
        $order->errors;
        if ($is_success) {
            $msg = '创建订单成功';
            return $this->send($order, $msg);
        } else {
            $msg = '创建订单失败';
            return $this->send($order->errors, $msg, 0);
        }
    }

    /**
     * @api {POST} v1/order/append-order 追加订单(xieyi 90% 目前产品已删除该需求 )
     *
     * @apiName ActionAppendOrder
     * @apiGroup Order
     *
     * @apiDescription 追加订单
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_service_type_id 服务类型商品id
     * @apiParam {String} order_src_id 订单来源id 访问源(android_4.2.2)
     * @apiParam {String} order_booked_begin_time 服务开始时间
     * @apiParam {String} order_booked_end_time 服务结束时间
     * @apiParam {String} order_customer_phone 用户手机号
     * @apiParam {String} order_parent_id 追加父订单id
     * @apiParam {String} order_pay_type 支付方式 1现金 2线上 3第三方 必填
     * @apiParam {String} address_id 订单地址id
     * @apiParam {String} [address] 订单地址
     * @apiParam {String} [city]城市
     * @apiParam {String} [order_pop_order_code] 第三方订单号
     * @apiParam {String} [order_pop_group_buy_code] 第三方团购号
     * @apiParam {Integer} [order_pop_order_money] 第三方订单金额,预付金额
     * @apiParam {String} [coupon_id] 优惠劵id
     * @apiParam {String} [channel_id] 下单渠道
     * @apiParam {String} [order_booked_worker_id] 指定阿姨id
     * @apiParam {Number} [order_customer_need] 客户需求
     * @apiParam {String} [order_customer_memo] 客户备注
     * @apiParam {Integer} [order_is_use_balance] 是否使用余额 1 使用 0 不适用 默认1
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/order/action-append-order
     *
     * @apiSuccess {Object} order 成功订单对象.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "以下单成功，正在等待阿姨抢单",
     *       "ret":{
     *           "order": {}
     *
     *       }
     *
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionAppendOrder()
    {
        $args = Yii::$app->request->post() or
                $args = json_decode(Yii::$app->request->getRawBody(), true);
        $attributes = [];
        $user = CustomerAccessToken::getCustomer($args['access_token']);
        if (is_null($user)) {
            return $this->send(null, "用户无效,请先登录", 0);
        }
        $attributes['customer_id'] = $user->id;
        if (is_null($args['order_service_type_id'])) {
            return $this->send(null, "请输入商品类型", 0);
        }
        $attributes['order_service_type_id'] = $args['order_service_type_id'];
        if (is_null($args['order_src_id'])) {
            return $this->send(null, "数据不完整,缺少订单来源", 0);
        }
        $attributes['order_src_id'] = $args['order_src_id'];

        if (is_null($args['order_booked_begin_time'])) {
            return $this->send(null, "数据不完整,请输入初始时间", 0);
        }
        $attributes['order_booked_begin_time'] = $args['order_booked_begin_time'];

        if (is_null($args['order_booked_end_time'])) {
            return $this->send(null, "数据不完整,请输入完成时间", 0);
        }
        $attributes['order_booked_end_time'] = $args['order_booked_end_time'];

        if (is_null($args['order_pay_type'])) {
            return $this->send(null, "数据不完整,请输入支付方式", 0);
        }
        $attributes['order_pay_type'] = $args['order_pay_type'];


        if (is_null($args['address_id'])) {
            if (is_null($args['address_id']) or is_null($args['city'])) {
                return $this->send(null, "数据不完整,请输入常用地址id或者城市,地址名", 0);
            }
            $model = CustomerAddress::addAddress($user->id, $args['city'], $args['address'], $args['order_customer_phone'], $args['order_customer_phone']);
            $attributes['address_id'] = $model->id;
        } else {
            $attributes['address_id'] = $args['address_id'];
        }

        if (isset($args['order_pop_order_code'])) {
            $attributes['order_pop_order_code'] = $args['order_pop_order_code'];
        }

        if (isset($args['order_pop_group_buy_code'])) {
            $attributes['order_pop_group_buy_code'] = $args['order_pop_group_buy_code'];
        }

        if (isset($args['coupon_id'])) {
            $attributes['coupon_id'] = $args['coupon_id'];
        }

        if (isset($args['channel_id'])) {
            $attributes['channel_id'] = $args['channel_id'];
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

        $attributes['order_ip'] = Yii::app()->request->userHostAddress;
        $attributes['admin_id'] = 0;
        $order = new \core\models\order\Order();
        $is_success = $order->createNew($attributes);
        if ($is_success) {
            $msg = '追加订单成功';
            $this->send($order, $msg);
        } else {
            $msg = '追加订单失败';
            $this->send($order, $msg, 0);
        }
    }

    /**
     * @api {GET} /order/orders 查询用户订单(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     *
     * @apiName Orders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户令牌
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间
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
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *    {
     *    "id": "2",
     *    "order_code": "339710",
     *    "order_parent_id": "0",
     *    "order_is_parent": "0",
     *    "created_at": "1445347126",
     *    "updated_at": "1445347126",
     *    "isdel": "0",
     *    "ver": "3",
     *    "version": "3",
     *    "order_ip": "58.135.77.96",
     *    "order_service_type_id": "1",
     *    "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *    "order_src_id": "1",
     *    "order_src_name": "BOSS",
     *    "channel_id": "20",
     *    "order_channel_name": "后台下单",
     *    "order_unit_money": "20.00",
     *    "order_money": "40.00",
     *    "order_pay_type": "支付方式",
     *    "order_booked_count": "120",
     *    "order_booked_begin_time": "1446249600",
     *    "order_booked_end_time": "1446256800",
     *    "address_id": "397",
     *    "district_id": "3",
     *    "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *    "order_booked_worker_id": "0",
     *    "checking_id": "0",
     *    "order_cs_memo": "",
     *    "order_id": "2",
     *    "order_before_status_dict_id": "2",
     *    "order_before_status_name": "已支付",
     *    "order_status_dict_id": "3",
     *    "order_status_name": "已开始智能指派"
     *    }
     *    ]
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionOrders()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];

        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 0);
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

        try {
            $orderSearch = new \core\models\order\OrderSearch();
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

            $this->send($ret, "操作成功", 1);
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误" . $e, 0, 1024);
        }
    }

    /**
     * @api {GET} /order/orders-count 查询用户订单数量(xieyi 70%缺少周期订单)
     *
     * @apiName OrdersCount
     * @apiGroup Order
     * @apiDescription 获得用户各种状态的订单数量
     *
     * @apiParam {String} access_token 用户令牌
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间     *
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
     *      }
     *     }
     *
     *
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 0);
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

        $orderSearch = new \core\models\order\OrderSearch();
        $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus, $channels, $from, $to);
        $ret['count'] = $count;
        return $this->send($ret, "操作成功", 1, 200);
    }

    /**
     * @api {GET} /order/worker-orders 查询阿姨订单(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     * @apiName WorkerOrders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间
     * @apiParam {String} [oc.customer_id]客户id
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
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *    {
     *    "id": "2",
     *    "order_code": "339710",
     *    "order_parent_id": "0",
     *    "order_is_parent": "0",
     *    "created_at": "1445347126",
     *    "updated_at": "1445347126",
     *    "isdel": "0",
     *    "ver": "3",
     *    "version": "3",
     *    "order_ip": "58.135.77.96",
     *    "order_service_type_id": "1",
     *    "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *    "order_src_id": "1",
     *    "order_src_name": "BOSS",
     *    "channel_id": "20",
     *    "order_channel_name": "后台下单",
     *    "order_unit_money": "20.00",
     *    "order_money": "40.00",
     *    "order_booked_count": "120",
     *    "order_booked_begin_time": "1446249600",
     *    "order_booked_end_time": "1446256800",
     *    "address_id": "397",
     *    "district_id": "3",
     *    "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *    "order_booked_worker_id": "0",
     *    "checking_id": "0",
     *    "order_cs_memo": "",
     *    "order_id": "2",
     *    "order_before_status_dict_id": "2",
     *    "order_before_status_name": "已支付",
     *    "order_status_dict_id": "3",
     *    "order_status_name": "已开始智能指派"
     *    }
     *    ]
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerOrders()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];

        $worker = WorkerAccessToken::getWorker($token);

        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0);
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

        $args["owr.worker_id"] = $worker->id;
        try {
            $orderSearch = new \core\models\order\OrderSearch();
            $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $orderStatus, $channels, $from, $to);
            $orders = $orderSearch->searchWorkerOrdersWithStatus($args, $isAsc, $offset, $limit, $orderStatus, $channels, $from, $to);
        } catch (\Exception $e) {
            return $this->send($e, "服务异常", 2);
        }
        $ret = [];
        $ret['limit'] = $limit;
        $ret['page_total'] = ceil($count / $limit);
        $ret['page'] = $page;
        $ret['orders'] = $orders;
        $this->send($ret, "操作成功", 1);
    }

    /**
     * @api {GET} /order/worker-service-orders 查询待服务阿姨订单(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     *
     * @apiName WorkerServiceOrders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [page] 第几页
     * @apiParam {String} [limit] 每页包含订单数
     * @apiParam {String} [is_asc] 排序方式
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间
     * @apiParam {String} [oc.customer_id]客户id
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
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *    {
     *    "id": "2",
     *    "order_code": "339710",
     *    "order_parent_id": "0",
     *    "order_is_parent": "0",
     *    "created_at": "1445347126",
     *    "updated_at": "1445347126",
     *    "isdel": "0",
     *    "ver": "3",
     *    "version": "3",
     *    "order_ip": "58.135.77.96",
     *    "order_service_type_id": "1",
     *    "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *    "order_src_id": "1",
     *    "order_src_name": "BOSS",
     *    "channel_id": "20",
     *    "order_channel_name": "后台下单",
     *    "order_unit_money": "20.00",
     *    "order_money": "40.00",
     *    "order_booked_count": "120",
     *    "order_booked_begin_time": "1446249600",
     *    "order_booked_end_time": "1446256800",
     *    "address_id": "397",
     *    "district_id": "3",
     *    "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *    "order_booked_worker_id": "0",
     *    "checking_id": "0",
     *    "order_cs_memo": "",
     *    "order_id": "2",
     *    "order_before_status_dict_id": "2",
     *    "order_before_status_name": "已支付",
     *    "order_status_dict_id": "3",
     *    "order_status_name": "已开始智能指派"
     *    }
     *    ]
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerServiceOrders()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];

        $worker = WorkerAccessToken::getWorker($token);

        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0);
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
        try {
            $orderSearch = new \core\models\order\OrderSearch();
            $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr, null, $from, $to);
            $orders = $orderSearch->searchWorkerOrdersWithStatus($args, $isAsc, $offset, $limit, $arr);
        } catch (Exception $e) {
            return $this->send($e, "服务异常", 2);
        }
        $ret = [];
        $ret['limit'] = $limit;
        $ret['page_total'] = ceil($count / $limit);
        $ret['page'] = $page;
        $ret['orders'] = $orders;
        $this->send($ret, "操作成功", 1);
    }

    /**
     * @api {GET} /order/worker-orders-count 查询阿姨订单订单数量(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     *
     * @apiName WorkerOrdersCount
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} [id] 订单id
     * @apiParam {String} [channels] 渠道号按'.'分隔
     * @apiParam {String} [order_status] 订单状态按'.'分隔
     * @apiParam {String} [oc.customer_id]客户id
     * @apiParam {String} [from] 开始时间
     * @apiParam {String} [to] 结束时间
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
     *
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0, 403);
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
        $orderSearch = new \core\models\order\OrderSearch();
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
     * @api {GET} /order/worker-service-orders-count 查询阿姨待服务订单订单数量
     *
     *
     * @apiName WorkerServiceOrdersCount
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
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
     *
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerServiceOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0, 403);
        }

        $args["owr.worker_id"] = $worker->id;
        $orderSearch = new \core\models\order\OrderSearch();
        $ret = [];
        $arr = array();
        $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;

        $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr);
        $ret['count'] = $count;

        $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} v1/order/worker-done-orders-history 查询阿姨三个月的完成历史订单(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     *
     * @apiName WorkerDoneOrdersHistory
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} [page] 第几页 从第一页开始
     * @apiParam {String} [limit] 每页包含订单数
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
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *    {
     *    "id": "2",
     *    "order_code": "339710",
     *    "order_parent_id": "0",
     *    "order_is_parent": "0",
     *    "created_at": "1445347126",
     *    "updated_at": "1445347126",
     *    "isdel": "0",
     *    "ver": "3",
     *    "version": "3",
     *    "order_ip": "58.135.77.96",
     *    "order_service_type_id": "1",
     *    "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *    "order_src_id": "1",
     *    "order_src_name": "BOSS",
     *    "channel_id": "20",
     *    "order_channel_name": "后台下单",
     *    "order_unit_money": "20.00",
     *    "order_money": "40.00",
     *    "order_booked_count": "120",
     *    "order_booked_begin_time": "1446249600",
     *    "order_booked_end_time": "1446256800",
     *    "address_id": "397",
     *    "district_id": "3",
     *    "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *    "order_booked_worker_id": "0",
     *    "checking_id": "0",
     *    "order_cs_memo": "",
     *    "order_id": "2",
     *    "order_before_status_dict_id": "2",
     *    "order_before_status_name": "已支付",
     *    "order_status_dict_id": "3",
     *    "order_status_name": "已开始智能指派"
     *    }
     *    ]
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerDoneOrdersHistory()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];

        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0);
        }
        $beginTime = strtotime('-3 month');
        $endTime = time();

        @$limit = $args["access_token"];
        if (is_null($limit)) {
            $limit = 10;
        }
        @$page = $args["page"];
        if (is_null($page)) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $ret = OrderSearch::getWorkerAndOrderAndDoneTime($worker->id, $beginTime, $endTime, $limit, $offset);
        return $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} v1/order/worker-cancel-orders-history 查询阿姨三个月的完成历史订单(xieyi 90%已经将后台接口完成,缺少周期订单)
     *
     *
     * @apiName WorkerCancelOrdersHistory
     * @apiGroup Order
     *
     * @apiParam {String} access_token 阿姨登陆令牌
     * @apiParam {String} [page] 第几页 从第一页开始
     * @apiParam {String} [limit] 每页包含订单数
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
     *    "limit": "1",
     *    "page_total": 4,
     *    "offset": 0,
     *    "orders": [
     *    {
     *    "id": "2",
     *    "order_code": "339710",
     *    "order_parent_id": "0",
     *    "order_is_parent": "0",
     *    "created_at": "1445347126",
     *    "updated_at": "1445347126",
     *    "isdel": "0",
     *    "ver": "3",
     *    "version": "3",
     *    "order_ip": "58.135.77.96",
     *    "order_service_type_id": "1",
     *    "order_service_type_name": "Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机",
     *    "order_src_id": "1",
     *    "order_src_name": "BOSS",
     *    "channel_id": "20",
     *    "order_channel_name": "后台下单",
     *    "order_unit_money": "20.00",
     *    "order_money": "40.00",
     *    "order_booked_count": "120",
     *    "order_booked_begin_time": "1446249600",
     *    "order_booked_end_time": "1446256800",
     *    "address_id": "397",
     *    "district_id": "3",
     *    "order_address": "北京,北京市,朝阳区,SOHO一期2单元908,测试昵称,18519654001",
     *    "order_booked_worker_id": "0",
     *    "checking_id": "0",
     *    "order_cs_memo": "",
     *    "order_id": "2",
     *    "order_before_status_dict_id": "2",
     *    "order_before_status_name": "已支付",
     *    "order_status_dict_id": "3",
     *    "order_status_name": "已开始智能指派"
     *    }
     *    ]
     *    }
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionWorkerCancelOrdersHistory()
    {
        $args = Yii::$app->request->get();
        @$token = $args["access_token"];

        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0);
        }
        $beginTime = strtotime('-3 month');
        $endTime = time();

        @$limit = $args["access_token"];
        if (is_null($limit)) {
            $limit = 10;
        }
        @$page = $args["page"];
        if (is_null($page)) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $ret = OrderSearch::getWorkerAndOrderAndCancelTime($worker->id, $beginTime, $endTime, $limit, $offset);
        return $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} /order/status-orders-count 查询用户不同状态订单数量(xieyi 70%缺少周期订单)
     *
     *
     * @apiName StatusOrdersCount
     * @apiGroup Order
     * @apiDescription 获得各种状态的订单数量
     * @apiParam {String} access_token 订单状态
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
     *     HTTP/1.1 200 OK
     *     {
     *      "code": "1",
     *      "msg": "操作成功",
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
     *
     *
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionStatusOrdersCount()
    {
        $args = Yii::$app->request->get();

        @$token = $args["access_token"];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 0, 403);
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
        $orderSearch = new \core\models\order\OrderSearch();
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
        $this->send($ret, "操作成功");
    }

    /**
     * @api {GET} /order/order-status-history 查询用户某个订单状态历史状态记录(xieyi 70%缺少周期订单)
     *
     *
     * @apiName OrderStatusHistory
     * @apiGroup Order
     *
     * @apiParam {String} order_id 订单id
     * @apiParam {String} access_token 认证令牌
     *
     * @apiSuccess {Object[]} status_list 该状态订单.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "code": "ok",
     *         "msg": "操作成功",
     *         "ret": [
     *         {
     *         "id": 2,
     *         "created_at": 1445347126,
     *         "updated_at": 1445347126,
     *         "order_id": "2",
     *         "order_before_status_dict_id": 1,
     *         "order_before_status_name": "已创建",
     *         "order_status_dict_id": 1,
     *         "order_status_name": "已创建",
     *         "admin_id": 1,
     *         "order_flag_lock_time": null
     *         },
     *         {
     *         "id": 3,
     *         "created_at": 1445347126,
     *         "updated_at": 1445347126,
     *         "order_id": "2",
     *         "order_before_status_dict_id": 1,
     *         "order_before_status_name": "已创建",
     *         "order_status_dict_id": 2,
     *         "order_status_name": "已支付",
     *         "admin_id": 1,
     *         "order_flag_lock_time": null
     *         },
     *         {
     *         "id": 4,
     *         "created_at": 1445347126,
     *         "updated_at": 1445347126,
     *         "order_id": "2",
     *         "order_before_status_dict_id": 2,
     *         "order_before_status_name": "已支付",
     *         "order_status_dict_id": 3,
     *         "order_status_name": "已开始智能指派",
     *         "admin_id": 1,
     *         "order_flag_lock_time": null
     *         }
     *         ]
     *         }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionOrderStatusHistory()
    {

        $args = Yii::$app->request->get() or
                $args = json_decode(Yii::$app->request->getRawBody(), true);
        @$token = $args['access_token'];
        $user = CustomerAccessToken::getCustomer($token);
        if (empty($user)) {
            return $this->send(null, "用户无效,请先登录", 0);
        }
        $orderId = $args['order_id'];
        if (!is_numeric($orderId)) {
            return $this->send(null, "该订单不存在", 0);
        }
        //TODO check whether the orders belong the user
        $orderSearch = new \core\models\order\OrderSearch();
        $orderArr = array();
        $orderArr["id"] = $orderId;
        $orderArr['order_parent_id'] = 0;
        $orders = $orderSearch->searchOrdersWithStatus($orderArr);
        $ret['status_history'] = \core\models\order\OrderStatus::searchOrderStatusHistory($orderId);
        $ret['orders'] = $orders;
        $this->send($ret, "操作成功");
    }

    /**
     * @api {PUT} /order/cancel-order 取消订单(haojianse 100% )
     *
     * @apiName CancelOrder
     * @apiGroup Order
     *
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [order_cancel_reason] 取消原因
     * @apiParam {String} order_id 订单号
     *
     * @apiParam {String} recursive_order_id 周期订单
     * @apiParam {String} order_id 订单id
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "693345订单取消成功",
     *       "ret":{
     *         1
     *       }
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionCancelOrder()
    {
        $param = json_decode(Yii::$app->request->getRawBody(), true);

        if (!isset($param['access_token']) || !$param['access_token'] || !isset($param['order_id']) || !$param['order_id']) {
            return $this->send(null, "验证码或订单号不能为空", 0, 403);
        }

        $token = $param['access_token'];
        $orderId = $param['order_id'];
        $reason = '';

        if (isset($param['order_cancel_reason'])) {
            $reason = $param['order_cancel_reason'];
        }

        if (!CustomerAccessToken::checkAccessToken($token)) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($token);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * access_token和订单验证
             * $customer->id 用户
             * $order_id     订单号
             */
            $orderValidation = Order::validationOrderCustomer($customer->id, $orderId);

            if ($orderValidation) {
                /**
                 * $order_id订单号
                 * $amdin_id管理员id,没有请填写0
                 * $param['order_cancel_reason'] 取消原因
                 *
                 */
                $order_cancel_reason = array(
                    '临时有事，改约',
                    '信息填写有误，重新下单',
                    '不需要服务了',
                );
                if (!in_array($reason, $order_cancel_reason)) {
                    $reason = '其他原因#' . $reason;
                }
                try {
                    $result = Order::cancel($orderId, 0, $reason);
                    if ($result) {
                        return $this->send([1], $orderId . "订单取消成功", 1);
                    } else {
                        return $this->send([0], $orderId . "订单取消失败", 0);
                    }
                } catch (Exception $e) {
                    return $this->send(null, $orderId . "订单取消异常:" . $e);
                }
            } else {
                return $this->send(null, "核实用户订单唯一性失败，用户id：" . $customer->id . ",订单id：" . $orderId, 0, 403);
            }
        } else {
            return $this->send(null, "获取客户信息失败.access_token：" . $token, 0, 403);
        }
    }

    /**
     * @api {GET} /order/add-comment 评价订单（该功能写在UserController里面 v1/user/user-suggest）
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiName AddComment
     * @apiGroup Order
     *
     * @apiParam {String} order_id 订单id
     * @apiParam {String} sub_id 子订单id
     * @apiParam {String} content 评价内容
     * @apiParam {String} is_anonymous 是否匿名评价
     * @apiParam {String} rate 星级
     * @apiParam {String} tag 评价标签
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "订单评价成功成功",
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    public function actionAddComment()
    {
        
    }

    /**
     * @api {DELETE} /order/hiden-order 删除订单（郝建设 100%）
     *
     *
     * @apiName HidenOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} order_id 订单号
     * @apiDescription  客户端删除订单，后台软删除 隐藏订单
     * @apiParam {String} order_id 订单id
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "订单删除成功",
     *      "ret":{
     *         1
     *       }
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */
    public function actionHidenOrder()
    {
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * access_token和订单验证
             * $customer->id 用户
             * $order_id     订单号
             */
            try {
                $orderValidation = \core\models\order\Order::validationOrderCoustomer($customer->id, $param['order_id']);

                if (\core\models\order\Order::customerDel($param['order_id'], 0)) {
                    return $this->send([1], "删除订单成功", 1);
                } else {
                    return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024);
            }
        }
    }

    /**
     * @api {get} v1/order/worker-history-orders.php 阿姨全部订单月份列表(zhaoshunli 0%)
     * @apiName actionAllOrderCommon
     * @apiGroup Order
     * @apiDescription 对账日常订单查看全部，月份列表
     * @apiParam {String} access_token    会话id.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
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
     *          "msgStyle": "",
     *          "alertMsg": ""
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */
    public function actionWorkerHistoryOrders()
    {
        
    }

    /**
     * @api {get} v1/order/get-worker-orders 指定阿姨订单数/待抢单订单订单数/指定阿姨订单列表/待抢单订单列表 (haojianshe 100%)
     * @apiName actionGetWorkerOrders
     * @apiGroup Order
     * @apiDescription 阿姨抢单数
     * @apiParam {String} access_token      会话id.
     * @apiParam {String} platform_version  平台版本号
     * @apiParam {String} [page_size]         条数  #leveltype =2 时要传递
     * @apiParam {String} [page]              页面  #leveltype =2 时要传递
     * @apiParam {String} leveltype         判断标示 leveltype=1 指定阿姨订单数，待抢单订单订单数;  leveltype=2 指定阿姨订单列表，待抢单订单列表,指定阿姨订单数，待抢单订单订单数
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *  指定阿姨订单数/待抢单订单订单数 leveltype=1
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "workerData": "指定阿姨订单数",
     *          "orderData": "待抢单订单数",
     *          "workerServiceCount": "待服务订单数",
     *          "worker_is_block": 
     *            {
     *            ##暂时还没有统一
     *            //"阿姨状态 0正常1封号",
     *            }
     *      }
     * }
     *
     *   * 指定阿姨订单列表/待抢单订单列表 leveltype=2
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "workerData":
     *           [
     *           {
     *            "order_id":"订单号"
     *            "order_code":"订单编号"
     *            "batch_code":"周期订单号"
     *            "booked_begin_time":"服务开始时间"
     *            "booked_end_time":"服务结束时间"
     *            "channel_name":"服务类型名称"
     *            "booked_count":"时常"
     *            "address":"服务地址"
     *            "need":"备注说明"
     *            "money":"订单价格"
     *           } 
     *         ],
     *         "time":172800   倒计时秒 #要求2天
     *         "pageNumber":""  指定阿姨订单数
     *         "pageNumbNot":"" 待抢单订单数
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */
    public function actionGetWorkerOrders()
    {

        $param = Yii::$app->request->get();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        $worker = WorkerAccessToken::getWorker($param['access_token']);

        if (empty($param['leveltype']) || empty($param['access_token'])) {
            return $this->send(null, "缺少规定的参数", 0, 403);
        }
        if (!empty($worker) && !empty($worker->id)) {
            if ($param['leveltype'] == 2) {
                if (empty($param['page_size']) || empty($param['page'])) {
                    return $this->send(null, "缺少规定的参数,page_size或page不能为空", 0, 403);
                }
                try {
                    #指定阿姨订单列表
                    $workerCount = OrderSearch::getPushWorkerOrders($worker->id, $param['page_size'], $param['page'], 1);
                    #未指定阿姨订单列表
                    $workerCountTwo = OrderSearch::getPushWorkerOrders($worker->id, $param['page_size'], $param['page'], 0);

                    #指定阿姨订单数
                    $workerOrderCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 0);

                    if ($param['page'] == 1) {
                        $ret['pageNumber'] = ($workerOrderCount / $param['page_size']) + 1;
                    }

                    #待抢单订单数
                    $orderData = OrderSearch::getPushWorkerOrdersCount($worker->id, 1);

                    if ($param['page'] == 1) {
                        $ret['pageNumbNot'] = ($orderData / $param['page_size']) + 1;
                    }

                    $ret['workerData'] = array_merge($workerCount, $workerCountTwo);
                    #倒计时
                    $ret['time'] = 172800;
                    return $this->send($ret, $this->workerText[$param['leveltype']], 1);
                } catch (\Exception $e) {
                    return $this->send(null, "boss系统错误," . $e . $this->workerText[$param['leveltype']], 1024);
                }
            } else if ($param['leveltype'] == 1) {
                try {

                    #指定阿姨订单数
                    $workerCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 0);
                    #待抢单订单数
                    $workerCountTwo = OrderSearch::getPushWorkerOrdersCount($worker->id, 1);
                    $args["owr.worker_id"] = $worker->id;
                    $args["oc.customer_id"] = null;
                    $orderSearch = new \core\models\order\OrderSearch();
                    $ret = [];
                    $arr = array();
                    $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
                    $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
                    $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;
                    $count = $orderSearch->searchWorkerOrdersWithStatusCount($args, $arr);
                    #待服务订单数
                    $ret['workerServiceCount'] = $count;
                    $ret['workerData'] = $workerCount;
                    $ret['orderData'] = $workerCountTwo;
                    #阿姨状态
                    $ret['worker_is_block'] = [
                        $worker->worker_is_block
                    ];
                    return $this->send($ret, $this->workerText[$param['leveltype']], 1);
                } catch (\Exception $e) {
                    return $this->send(null, "boss系统错误," . $e . $this->workerText[$param['leveltype']], 1024);
                }
            } else {
                return $this->send(null, "leveltype指定参数错误,不能大于2", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * @api {POST} v1/order/create-recursive-orderes 创建周期订单 （haojianshe 100%）
     *
     * @apiName CreateRecursiveOrderes
     * @apiGroup Order
     * @apiDescription 周期订单提交
     *
     * @apiParam  {String}  access_token      会话id. 必填
     * @apiParam  {String}  [platform_version]  平台版本号
     * @apiParam  {integer} order_service_type_id 服务类型 商品id 必填
     * @apiParam  {integer} order_src_id 订单来源id 必填
     * @apiParam  {string}  channel_id 下单渠道 必填
     * @apiParam  {int}     address_id 客户地址id 必填
     * @apiParam  {string}  order_customer_phone 客户手机号 必填
     * @apiParam  {int}     admin_id 操作人id 0客户 1系统 必填
     * @apiParam  {int}     order_pay_type 支付方式 1现金 2线上 3第三方 必填
     * @apiParam  {int}     order_is_use_balance 是否使用余额 0否 1是 必填
     * @apiParam  {string}  [order_booked_worker_id] 指定阿姨id
     * @apiParam  {int}  [accept_other_aunt] 0不接受 1接受
     * @apiParam  {string}  [order_customer_need] 客户需求
     * @apiParam  {string}  [order_customer_memo] 客户备注
     * @apiParam   {int} [coupon_id] 优惠券id
     * 
     * @apiParam  {Object[]}order_booked_time
     * [
     * {"order_booked_begin_time":"2015-10-01 10:10","order_booked_end_time":"2015-10-02 10:10"},
     * {"order_booked_begin_time":"2015-10-03 10:10","order_booked_end_time":"2015-10-04 10:10"},
     * {"order_booked_begin_time":"2015-10-05 10:10","order_booked_end_time":"2015-10-06 10:10"},
     * {"order_booked_begin_time":"2015-10-07 10:10","order_booked_end_time":"2015-10-08 10:10"}
     * ]
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"添加成功成功",
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "操作失败"
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
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        #获取用户ip
        $order_ip = Yii::$app->getRequest()->getUserIP();

        #判断服务类型
        if (empty($param['order_service_type_id'])) {
            return $this->send(null, "服务类型不能为空", 0);
        }
        #判断订单来源
        if (empty($param['order_src_id'])) {
            return $this->send(null, "订单来源id不能为空", 0);
        }
        #判断下单渠道不能为空
        if (empty($param['channel_id'])) {
            return $this->send(null, "下单渠道不能为空", 0);
        }
        #判断地址不能为空
        if (empty($param['address_id'])) {
            return $this->send(null, "用户地址不能为空", 0);
        }
        #判断客户手机不能为空
        if (empty($param['order_customer_phone'])) {
            return $this->send(null, "客户手机不能为空", 0);
        }

        #判断支付方式
        if (empty($param['order_pay_type'])) {
            return $this->send(null, "支付方式不能为空", 0);
        }
        #判断是否使用余额
        if (empty($param['order_is_use_balance'])) {
            return $this->send(null, "使用余额不能为空", 0);
        }
        if(empty($param['$order_booked_time'])){
            return $this->send(null, "服务时间不能为空", 0);
        }


        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            $attributes = array(
                "order_ip" => $order_ip,
                "order_service_type_id" => $param['order_service_type_id'],
                "order_src_id" => $param['order_src_id'],
                "channel_id" => $param['channel_id'],
                "address_id" => $param['address_id'],
                "customer_id" => $customer->id,
                "order_customer_phone" => $param['order_customer_phone'],
                "admin_id" => 0,
                "order_pay_type" => $param['order_pay_type'],
                "order_is_use_balance" => $param['order_is_use_balance'],
                "order_booked_worker_id" => $param['order_booked_worker_id'],
                "order_customer_need" => $param['order_customer_need'],
                "order_customer_memo" => $param['order_customer_memo']
            );

            $booked_list = array();

            foreach ($param['$order_booked_time'] as $key => $val) {
                $booked_list[]=[
                    'order_booked_begin_time' => strtotime($val['order_booked_begin_time']),
                    'order_booked_end_time' => strtotime($val['order_booked_end_time'])
                ];
            }

            try {
                $order = new \core\models\order\Order();
                $createOrder = $order->createNewBatch($attributes, $booked_list);
                if ($createOrder['status'] == 1) {
                    #if ($createOrder["errors"]["order_service_type_name"][0])
                    if (!empty($createOrder)) {
                        return $this->send([1], "添加成功", 1);
                    } else {
                        return $this->send(null, "添加失败", 0, 403);
                    }
                } else {
                    return $this->send(null, "boss系统错误,添加周期订单失败", 1024);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误,添加周期订单失败" . $e, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {PUT} v1/order/set-worker-order 阿姨抢单提交 (haojianshe 100%)
     *
     * @apiName actionSetWorkerOrder
     * @apiGroup Order
     * @apiDescription 阿姨抢单提交
     *
     * @apiParam {String} access_token      会话id.
     * @apiParam {String} platform_version  平台版本号
     * @apiParam {String} order_id          订单号
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "操作失败"
     *  }
     *
     */
    public function actionSetWorkerOrder()
    {
        $param = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty($param['order_id']) || !WorkerAccessToken::getWorker($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {

            try {
                $setWorker = Order::sysAssignDone($param['order_id'], $worker->id);
                $ret['workerSend'] = $setWorker;
                return $this->send($ret, "操作成功", 1);
            } catch (Exception $e) {
                return $this->send(null, "boss系统错误,阿姨抢单提交", 1024);
            }
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

}

?>
