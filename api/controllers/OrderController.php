<?php

namespace api\controllers;

use core\models\order\OrderPush;
use Faker\Provider\DateTime;
use Yii;
use common\models\FinanceOrderChannel;
use common\models\OrderSrc;
use common\models\CustomerAddress as CommonCustomerAddress;
use core\models\order\Order;
use core\models\customer\CustomerAccessToken;
use core\models\customer\CustomerAddress;
use yii\web\Response;

class OrderController extends \api\components\Controller
{

    /**
     * @api {POST} /order/choose-service-time 可服务时间表 (20%赵顺利 block linhongyou provide the feature)
     *
     * @apiDescription 选择服务时间接口服务器依据用户的当前位置提供时间表
     * @apiName ChooseServiceTime
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} lng 经度
     * @apiParam {String} lat 纬度
     * @apiParam {String} show_common 是否使用常用阿姨
     * @apiParam {String} plan_time 计划服务时间
     * @apiParam {String} city 城市
     * @apiParam {String} service_item 服务种类
     *
     * @apiSuccess {Object[]} appointment 可选时间表.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "获取可服务时间表成功"
     *       "ret":{
     *          "appointment": [
     *              {
     *                  "date_format": "10月10日",
     *                  "date_stamp": 1444406400,
     *                  "week": "明天",
     *                  "have_worker": 1,
     *                  "hour": [
     *                      {
     *                          "time": "08:00-10:00",
     *                          "status": "0"
     *                      },
     *                      {
     *                          "time": "18:00-20:00",
     *                          "status": "1"
     *                      }
     *                  ]
     *              }
     *          ]
     *       }
     *     }
     *
     *
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
    public function actionChooseServiceTime()
    {
        $params = Yii::$app->request->post() or
                $params = json_decode(Yii::$app->request->getRawBody(), true);
        @$accessToken = $params['access_token'];

        if (empty($accessToken) && !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(empty($accessToken), "用户认证已经过期,请重新登录.", "error", 403);
        }
        $appointment = array();
        for ($i = 0; $i <= 7; $i++) {
            $item = [
                'date_format' => date('m月d日', strtotime('+' . $i . ' day')),
                'date_stamp' => time(date('m月d日', strtotime('+' . $i . ' day'))),
                'week' => $i == 1 ? '明天' : '',
                'have_worker' => '1',
                'hour' =>
                [
                    ['time' => '08:00-10:00',
                        'status' => '0']
                    ,
                    [
                        "time" => "18:00-20:00",
                        "status" => "1"
                    ]
                ]
            ];
            $appointment[] = $item;
        }

        $ret = ["appointment" => $appointment];
        return $this->send($ret, "获取可服务时间表成功", "ok");
    }

    /**
     * @api {POST} /order/create-order 创建订单 (90%xieyi  创建已完成 渠道号更改 依赖林洪优)
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
     *
     * @apiSuccess {Object} order 成功订单对象.
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/order/action-append-order
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * {
     *  "code": "ok",
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
     *       "code": "error",
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
            return $this->send(null, "用户无效,请先登录");
        }
        $attributes['customer_id'] = $user->id;

        if (@is_null($args['server_item'])) {
            // server_item is null, order from app
            if (is_null($args['order_service_type_id'])) {
                return $this->send(null, "请输入商品类型");
            }
            $attributes['order_service_type_id'] = $args['order_service_type_id'];
        } else {
            // order from pop,第三方目前没有真实的order_service_type_id
            $attributes['order_service_type_id'] = 1; //$args['server_item'];
        }

        if (@is_null($args['order_src'])) {
            if (is_null($args['order_src_id'])) {
                return $this->send(null, "数据不完整,缺少订单来源");
            }
            $attributes['order_src_id'] = $args['order_src_id'];
        } else {
            $orderSrc = OrderSrc::find()->where(['order_src_name' => $args['order_src']])->one();
            if (!empty($orderSrc)) {
                $attributes['order_src_id'] = $orderSrc['id'];
            } else {
                return $this->send(null, "数据不完整,没有配置订单来源：" . $args['order_src']);
            }
        }

        if (is_null($args['order_booked_begin_time'])) {
            return $this->send(null, "数据不完整,请输入初始时间");
        }
        $attributes['order_booked_begin_time'] = $args['order_booked_begin_time'];

        if (is_null($args['order_booked_end_time'])) {
            return $this->send(null, "数据不完整,请输入完成时间");
        }
        $attributes['order_booked_end_time'] = $args['order_booked_end_time'];

        if (is_null($args['city'])) {
            return $this->send(null, "数据不完整,请输入城市名");
        }

        if (isset($args['address_id'])) {
            $attributes['address_id'] = $args['address_id'];
        } elseif (isset($args['address'])) {
            //add address into customer and return customer id
            $address = $args['address'];
            $customerAddress = CommonCustomerAddress::find()->where(['customer_id' => $user->id, 'customer_address_detail' => $address])->one();
            if (!empty($customerAddress)) {
                // found the address
                $attributes['address_id'] = $customerAddress['id'];
            } else {
                // address not found, add new address for the customer
                $area_name = $address;
                if (strpos($area_name, '市') > 0) {
                    $area_name = substr($area_name, strpos($area_name, '市') + strlen('市'));
                }
                if (strpos($area_name, '区') > 0) {
                    $area_name = substr($area_name, 0, strpos($area_name, '区'));
                }
                $model = CustomerAddress::addAddress($user->id, $area_name, $address, $args['order_customer_phone'], $args['order_customer_phone']);
                if (!empty($model)) {
                    $attributes['address_id'] = $model->id;
                } else {
                    return $this->send(null, "地址数据不完整,请输入常用地址id或者城市,地址名（包括区）");
                }
            }
        } else {
            return $this->send(null, "数据不完整,请输入常用地址id或者城市,地址名");
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
        } elseif (isset($args['order_channel_name'])) {
            $orderChannel = FinanceOrderChannel::find()->where(['finance_order_channel_name' => $args['order_channel_name']])->one();
            if (!empty($orderChannel)) {
                $attributes['channel_id'] = $orderChannel['id'];
            }
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
            $this->send($order, $msg);
        } else {
            $msg = '创建订单失败';
            $this->send($order->errors, $msg, "error");
        }
    }

    /**
     * @api {POST} v1/order/append-order 追加订单(xieyi 90%和创建订单一样)
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
     *       "code": "ok",
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
     *       "code": "error",
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
            return $this->send(null, "用户无效,请先登录");
        }
        $attributes['customer_id'] = $user->id;
        if (is_null($args['order_service_type_id'])) {
            return $this->send(null, "请输入商品类型");
        }
        $attributes['order_service_type_id'] = $args['order_service_type_id'];
        if (is_null($args['order_src_id'])) {
            return $this->send(null, "数据不完整,缺少订单来源");
        }
        $attributes['order_src_id'] = $args['order_src_id'];

        if (is_null($args['order_booked_begin_time'])) {
            return $this->send(null, "数据不完整,请输入初始时间");
        }
        $attributes['order_booked_begin_time'] = $args['order_booked_begin_time'];

        if (is_null($args['order_booked_end_time'])) {
            return $this->send(null, "数据不完整,请输入完成时间");
        }
        $attributes['order_booked_end_time'] = $args['order_booked_end_time'];

        if (is_null($args['address_id']) and ( is_null($args['address_id']) or is_null($args['city']))) {
            return $this->send(null, "数据不完整,请输入常用地址id或者城市,地址名");
        }
        if (is_null($args['address_id'])) {
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
            $this->send($order, $msg, "error");
        }
    }

    /**
     * @api {GET} /order/orders 查询订单(xieyi 100%已经将后台接口完成，创建也完成缺少测试)
     *
     *
     * @apiName Orders
     * @apiGroup Order
     *
     * @apiParam {String} access_token 订单状态
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [order_id] 订单id
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
     *    "code": "ok",
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
     *       "code": "error",
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
            return $this->send(null, "用户无效,请先登录",0);
        }

        if(isset($args['order_status'])){
            $orderStatus = explode(".",$args['order_status']);

        }
        $channels = null;
        if(isset($args['channels'])){
            $channels = explode(".",$args['channels']);
        }

        @$orderStatus = $args['order_status'];
        @$isAsc = $args['is_asc'];
        if (is_null($isAsc)) {
            $isAsc = true;
        }
        if (!isset($args['limit'])) {
            $limit = 10;
        }
        $page = 1;
        if (isset($args['page'])) {
            $page = $args['page'];
        }
        $offset = ($page-1)*$limit;
        @$from = $args['from'];
        @$to = $args['to'];
        $args["custum_id"] = $user->id;
        $orderSearch = new \core\models\order\OrderSearch();
        $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus);
        $orders = $orderSearch->searchOrdersWithStatus($args, $isAsc, $offset, $limit, $orderStatus, $channels,$from, $to);
        $ret = [];
        $ret['limit'] = $limit;
        $ret['page_total'] = ceil($count/$limit);
        $ret['page'] = $page;
        $ret['orders'] = $orders;
        $this->send($ret, $msg = "操作成功", $code = "1", $value = 200, $text = null);
    }

    /**
     * @api {GET} /order/orders-count 查询订单数量(xieyi 70%已经将后台接口完成，创建也完成缺少测试)
     *
     *
     * @apiName OrdersCount
     * @apiGroup Order
     *
     * @apiParam {String} access_token 订单状态
     * @apiParam {String} [order_status] 订单状态
     * @apiParam {String} [order_id] 订单id
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
     *       "code": "error",
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
            return $this->send(null, "用户无效,请先登录",0);
        }
        $orderStatus = null;
        if(isset($args['order_status'])){
            $orderStatus = explode(".",$args['order_status']);
        }
        $channels = null;
        if(isset($args['channels'])){
            $channels = explode(".",$args['channels']);
        }
        @$from = $args['from'];
        @$to = $args['to'];
        $args["custum_id"] = $user->id;
        $orderSearch = new \core\models\order\OrderSearch();
        $count = $orderSearch->searchOrdersWithStatusCount($args, $orderStatus,$channels);
        $ret['count'] = $count;
        $this->send($ret, $msg = "操作成功", $code = "1", $value = 200, $text = null);
    }

    /**
     * @api {GET} /order/status-orders-count 查询订单数量(xieyi 70%已经将后台接口完成，创建也完成缺少测试)
     *
     *
     * @apiName StatusOrdersCount
     * @apiGroup Order
     * @apiDescription 获得各种状态的订单数量
     * @apiParam {String} access_token 订单状态
     * @apiParam {String} [order_id] 订单id
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
     *       "code": "error",
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
            return $this->send(null, "用户无效,请先登录",0,403);
        }
        $orderStatus = null;
        if(isset($args['order_status'])){
            $orderStatus = explode(".",$args['order_status']);
        }
        $channels = null;
        if(isset($args['channels'])){
            $channels = explode(".",$args['channels']);
        }
        @$from = $args['from'];
        @$to = $args['to'];
        $args["custum_id"] = $user->id;
        $orderSearch = new \core\models\order\OrderSearch();
        $ret = [];
        if(!empty($orderStatus)){
            foreach($orderStatus as $statuStr){
                $count = $orderSearch->searchOrdersWithStatusCount($args, array($statuStr),$channels);
                $ret[$statuStr]=$count;
            }
        }else{
            $count = $orderSearch->searchOrdersWithStatusCount($args,$orderStatus,$channels);
            $ret['count']=$count;
        }
        $this->send($ret, $msg = "操作成功", $code = "1", $value = 200, $text = null);
    }

    /**
     * @api {GET} /order/order-status-history 查询某个订单状态历史状态记录(xieyi 70%已经将后台接口完成，创建也完成缺少测试)
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
            return $this->send(null, "用户无效,请先登录","0");
        }
        @$orderId = $args['order_id'];
        if(!is_numeric($orderId)){
            return $this->send(null, "该订单不存在","0");
        }
        $ret = \core\models\order\OrderStatus::searchOrderStatusHistory($orderId);

        $this->send($ret, $msg = "操作成功", $code = "ok", $value = 200, $text = null);
    }




    /**
     * @api {GET} /order/cancelorder 取消订单(haojianse %0  )
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} order_cancel_reason 取消原因
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
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * access_token和订单验证
             * $customer->id 用户
             * $order_id     订单号
             */
            $orderValidation = \core\models\order\Order::validationOrderCoustomer($customer->id, $param['order_id']);

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
                if (!in_array($param['order_cancel_reason'], $order_cancel_reason)) {
                    $param['order_cancel_reason'] = '其他原因#' . $param['order_cancel_reason'];
                }

                if (\core\models\order\Order::cancel($param['order_id'], 0, $param['order_cancel_reason'])) {
                    return $this->send([1], $param['order_id'] . "订单取消成功", "ok");
                }
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录.", "error", 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录222.", "error", 403);
        }
    }

    /**
     * @api {get} /mobileapidriver2/worker_request_order 抢单（xieyi %0）
     * @apiName actionDriverRequestOrder
     * @apiGroup Order
     * @apiDescription 阿姨抢单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id  订单id.
     * @apiParam {String} list_type  订单类型.
     * @apiParam {String} latitude
     * @apiParam {String} longitude
     * @apiParam {String} allow_worker_num   1  处理阿姨同时接单.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "result": "0",
     *          "msg": "抢单失败，当天该时间段已有其他订单",
     *          "goPage": 1,
     *          "isSuc": false,
     *          "telephone": "4006767636"
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
    public function actionObtainOrder()
    {
        
    }

    /**
     * @api {GET} /order/add-comment 评价订单（xieyi %0）
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
     * @api {GET} /order/hiden-order 删除订单（郝建设 100% ）
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
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * access_token和订单验证
             * $customer->id 用户
             * $order_id     订单号
             */
            $orderValidation = \core\models\order\Order::validationOrderCoustomer($customer->id, $param['order_id']);

            if (\core\models\order\Order::customerDel($param['order_id'], 0)) {
                return $this->send([], "删除订单成功", "ok");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
            }
        }
    }

    /**
     * @api {get} /order/search_push_order.php 获得推送订单信息 (xieyi 0%)
     * @apiName actionSearchPushOrder
     * @apiGroup Order
     * @apiDescription 推送过来的订单，通过id获取订单信息
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id  订单id.
     * @apiParam {String} service_type  1.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "orderInfo":
     *          [
     *          {
     *              "orderId": "1340",
     *              "reserveTime": "2015-10-14 16:00:52",
     *              "start_place": "密云县密云",
     *              "cityName": "北京",
     *              "user_type": "1",
     *              "longitude": "116.849716",
     *              "latitude": "40.382129",
     *              "extendInfo": "",
     *              "orderAllTime": "2.0",
     *              "orderInfoStr": "有顾客预约叫保洁。工作时间：2015-10-14 16:00:52，工作地点：密云县密云。备注：。距离你 0 公里",
     *              "serviceTypeName": "家庭保洁",
     *              "receiveOrderMsg": "您确认要接此订单吗？如果找不到客户家的路，请拨打客服电话，尽量不要骚扰客户",
     *              "distanceStr": "距我家 0 </font>km",
     *              "assign_status": 0,
     *              "small_maintail": []
     *          }
     *          ],
     *          "alertMsg": "请求成功"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */
    /**
     * @api {get} /mobileapidriver2/driver_get_now_order_list 待接活订单(zhaoshunli 0%)
     * @apiName actionDriverGetNowOrderList
     * @apiGroup Order
     * @apiDescription 阿姨查看带接活订单
     *
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} push          默认传0.
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "code":"OK",
     *     "msg":"待接单信息获取成功",
     *     "ret":
     *      {
     *          "locationType": 1,
     *          "result": 1,
     *          "orderListTitleStr": "",
     *          "allOrderList":
     *          [
     *          {
     *              "orderId": "374",
     *              "reserveTime": "2015-09-12 10:00:00",
     *              "start_place": "北京 光华路soho 301",
     *              "distance": "0.0",
     *              "cityName": "北京",
     *              "user_type": "0",
     *              "longitude": "116.459003",
     *              "latitude": "39.918741",
     *              "extendInfo": "测试测试",
     *              "orderAllTime": "2.0小时",
     *              "orderInfoStr": "有顾客预约叫保洁。工作时间：2015-09-12 10:00:00，工作地点：301。备注：测试测试。距离你0.0公里",
     *              "isVoiceOrder": "false",
     *              "orderReserveTime": "10:00",
     *              "orderReserveDate": "2015-09-12",
     *              "serviceTypeName": "家庭保洁",
     *              "timestamp": 1442023200,
     *              "showDate": "明天",
     *              "receiveOrderMsg": "您确认要接此订单吗？如果找不到客户家的路，请拨打客服电话，尽量不要骚扰客户。",
     *              "distanceStr": "距我当天第1个订单0.0</font>km"
     *          }
     *          ]
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError PositionNotFound 未找到坐标位置.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */
    /**
     * @api {get} /mobileapidriver2/worker_history_order 阿姨历史订单(zhaoshunli 100%)
     * @apiName actionWorkerHistoryOrder
     * @apiGroup Order
     * @apiDescription 阿姨查看历史订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"",
     *      "ret":
     *      {
     *          "result": 1,
     *          "msg": "",
     *          "cannelOrderList":
     *          [
     *          {
     *              "orderId": "244",
     *              "orderType": "家庭保洁",
     *              "orderPlace": "北京 海淀区 1334",
     *              "longitude": "116.353337",
     *              "latitude": "40.036104",
     *              "orderDate": "2015-09-21",
     *              "orderStartTime": "08:00",
     *              "orderEndTime": "10:00",
     *              "orderAllTime": "2.0小时",
     *              "userPhone": "13772427406",
     *              "userName": "",
     *              "cityName": "北京",
     *              "extendInfo": "无",
     *              "timestamp": 1442793600,
     *              "userType": "0"
     *          }
     *          ],
     *          "finishOrderList":
     *          [
     *          {
     *              "orderId": "174",
     *              "orderType": "家庭保洁",
     *              "orderPlace": "北京 海淀区定慧北里 6换10",
     *              "longitude": "0",
     *              "latitude": "0",
     *              "orderDate": "2015-09-17",
     *              "orderStartTime": "11:30",
     *              "orderEndTime": "13:30",
     *              "orderAllTime": "2.0小时",
     *              "userPhone": "13636363636",
     *              "userName": "",
     *              "cityName": "北京",
     *              "extendInfo": "无",
     *              "timestamp": 1442460600,
     *              "userType": "0"
     *          }
     *          ]
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
    /**
     * @api {get} v2/worker/account_checking.php 日常订单列表(zhaoshunli %0)
     * @apiName actionAccountChecking
     * @apiGroup Order
     * @apiDescription 对账首页，日常订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} page_num   默认传3.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "msgStyle": "",
     *          "alertMsg": "",
     *          "total_price": 88,
     *          "common_order_info":
     *          [
     *          {
     *              "order_id": "673",
     *              "finish_time": "2015年09月15日 12:00结束",
     *              "order_price": "100",
     *              "palce": "北京 朝阳区大悦城 测试测试",
     *              "cash": "0",
     *              "status": "未结算"
     *          }
     *          ]
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
    /**
     * @api {get} v2/worker/all_order_common.php 全部订单月份列表(zhaoshunli 0%)
     * @apiName actionAllOrderCommon
     * @apiGroup Order
     * @apiDescription 对账日常订单查看全部，月份列表
     * @apiParam {String} session_id    会话id.
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
    /**
     * @api {get} v2/worker/all_order_common_list.php 日常订单列表(zhaoshunli 0%)
     * @apiName actionAllOrderCommonList
     * @apiGroup Order
     * @apiDescription 对账日常订单，全部订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} page_num  每页显示多少条数据.
     * @apiParam {String} cur_page  当前页.
     * @apiParam {String} time  那个月份.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "title": "2015年09月 单数：8单 工时：23.5小时",
     *          "info":
     *          [
     *          {
     *              "order_id": "1115",
     *              "finish_time": "第39周 09月27日 12:00结束",
     *              "order_price": 50,
     *              "palce": "北京密云县密云1",
     *              "cash": "50",
     *              "status": "现金¥50"
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
    /**
     * @api {get} /v2/FixedUserOrder.php 固定客户以及订单列表(zhaoshunli 0%)
     * @apiName actionFixedUserOrder
     * @apiGroup Order
     * @apiDescription 对账固定客户首页，全部固定客户订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} period_id  周期id，首页传0.
     * @apiParam {String} worker_is_nearby  是否首页，首页传1.
     * @apiParam {String} per_page  每页显示多少条.
     * @apiParam {String} page  第几页.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "alertMsg": "",
     *          "fixedUserPeriod":
     *          [
     *          {
     *              "telephone": "13636363636",
     *              "street": "海淀区定慧北里",
     *              "place_detail": "6换10",
     *              "order_num": "2",
     *              "order_time": "5.5小时"
     *          }
     *          ],
     *          "fixedUserOrder":
     *          [
     *          {
     *              "wage_record_id": "0",
     *              "finish_time": "2015-09-11 18:37:04",
     *              "order_id": "174",
     *              "pay_worker_money": "87.5",
     *              "cash": "87.5",
     *              "is_fulltime": 0
     *          }
     *          ]
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

    /**
     * @api {get} v1/order/no_settlement_order_list.php  未结算订单(0%zhaoshunli)
     * @apiName actionNoSettlementOrderList
     * @apiGroup Order
     *
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} page_num   每页显示都少条数据.
     * @apiParam {String} cur_page  当前页.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "order_info":
     *          [
     *          {
     *              "order_id": "673",
     *              "finish_time": "2015年09月15日 12:00结束",
     *              "order_price": "100",
     *              "palce": "北京 朝阳区大悦城 测试测试",
     *              "cash": "0",
     *              "status": "未结算"
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
    public function actionPush($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return OrderPush::push($id);
    }

}

?>
