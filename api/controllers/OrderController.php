<?php

class OrderController
{
    /**
     *
     * @api {GET} /order/chooseservicetime 可服务时间表
     * 
     * @apiDescription 选择服务时间接口服务器依据用户的当前位置提供时间表
     * @apiName ChooseServiceTime
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
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
     *       "msg": ""
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
    public function actionChooseServiceTime(){
    
    }
    
    /**
     *
     * @api {GET} /order/requestorder 创建订单
     *
     *
     * @apiName RequestOrder
     * @apiGroup Order
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} lng 经度
     * @apiParam {String} lat 纬度
     * @apiParam {String} show_common 是否使用常用阿姨
     * @apiParam {String} plan_time 计划服务时间
     * @apiParam {String} is_used_worker 是否使用常用阿姨
     * @apiParam {String} city 城市名称
     * @apiParam {String} service_hour服务时长
     * @apiParam {String} street 街道小区
     * @apiParam {String} wanted_worker_id 使用阿姨id
     * @apiParam {String} service_date 计划服务日期
     * @apiParam {String} service_time 服务时长
     * @apiParam {String} custom_require 个性化需求
     * @apiParam {String} service_item 服务类型
     * @apiParam {String} coupon 优惠劵id
     * @apiParam {String} place_detail 详细地址精确到门牌号
     * @apiParam {String} pop_channel 订单推广渠道
     * @apiParam {Number} pay_amount 预付款
     * @apiParam {Number} worker_num 服务人数
     * @apiParam {String} extend_info 扩展信息
     * @apiParam {String} order_src 订单来源
     * 
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
    public function actionRequestOrder(){
    
    }
    
    /**
     *
     * @api {GET} /order/queryorders 查询订单
     *
     *
     * @apiName QueryOrder
     * @apiGroup Order
     *
     * @apiParam {String} order_status 订单状态
     * @apiParam {String} order_id 订单id
     * @apiParam {String} per_page 第几页
     * @apiParam {String} page_num 每页包含订单数
     *
     *
     * @apiSuccess {Object[]} orderList 该状态订单.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "以下单成功，正在等待阿姨抢单",
     *       "ret":{
     *           "orderList": [
     *              {
     *                  "extend_info": "没有特殊需求",
     *                  "id": "6925042",
     *                  "service_type": "9",
     *                  "place_detail": "北京市滚滚滚哈哈哈回家",
     *                  "reserve_time": "2015-09-17 09:00",
     *                  "status": "0",
     *                  "worker_list": "",
     *                  "create_time": "2015-09-15 19:26:27",
     *                  "user_id": "48080",
     *                  "is_paid": "0",
     *                  "active_code_id": "0",
     *                  "charge_reward_id": "0",
     *                  "reserve_type_id": "0",
     *                  "pop_channel": "App下单",
     *                  "order_id": "6925042",
     *                  "show_cancel": "0",
     *                  "service_main": "家电清洗",
     *                  "service_second": "油烟机清洗",
     *                  "create_way": "",
     *                  "sub_order": [],
     *                  "order_status": [
     *                      "2015-09-15 19:26 下单成功"
     *                  ],
     *                  "complain_status": [],
     *                  "active_code": "",
     *                  "active_code_value": "0",
     *                  "suggest_worked_time": "",
     *                  "show_appointment": "0"
     *              }
     *          ],
     *   
     *          "pageNum": "1",
     *          "totalPage": "2",
     *          "totalNum": "29"
     *      }
     *
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
    public function actionQueryOrder(){
    
    }
    
    
    /**
     *
     * @api {GET} /order/cancelorder 取消订单
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiName CancelOrder
     * @apiGroup Order
     *
     * @apiParam {String} recursive_order_id 周期订单
     * @apiParam {String} order_id 订单id
     *
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
    public function actionCancelOrder(){
    
    }
    
    /**
     *
     * @api {GET} /order/addcomment 评价订单
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
    public function actionAddComment(){
    
    }
    
    
    /**
     *
     * @api {GET} /order/hiddenorder 删除订单
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiName HiddenOrder
     * @apiGroup Order
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
     *
     */
    public function actionHidenOrder(){
        
    }
    
    /**
     * @api {get} /order/search_push_order.php 获得推送订单信息 
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
     * @api {get} /mobileapidriver2/driver_get_now_order_list 待接活订单
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
     * @api {get} /v2/auto_paid.php 报单
     * @apiName actionAutoPaid
     * @apiGroup Order
     * @apiDescription 报单-金额无误
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} work_time  工作时长.
     * @apiParam {String} order_id   订单id.
     * @apiParam {String} worker_id  阿姨id.
     * @apiParam {String} pay_money 报单金额.
     * @apiParam {String} city_name  城市.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"",
     *      "ret":
     *      {
     *          "comfirm_paid_id": "15",
     *          "alertMsg": "操作成功"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
     * @apiError WorkerIdNotFound 未找到阿姨ID.
     * @apiError PayMoneyIdNotFound 未找到报单金额.
     * @apiError CityNameNotFound 未找到城市信息.
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
     * @api {get} /mobileapidriver2/worker_history_order 阿姨历史订单
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
     * @api {get} v2/worker/account_checking.php 日常订单列表
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
     * @api {get} v2/worker/all_order_common.php 全部订单月份列表
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
     * @api {get} v2/worker/all_order_common_list.php 日常订单列表
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
     * @api {get} /v2/FixedUserOrder.php 固定客户以及订单列表
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
     * @api {get} /v2/worker/no_settlement_order_list.php  未结算订单
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
}

?>