<?php

class PayController
{
    /**
     * @api {get} /v2/auto_paid_confirm.php  结算-顾客现金支付
     * @apiName actionAutoPaidConfirm
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id   订单id.
     * @apiParam {String} pay_way 报单方式cash.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"",
     *      "ret":
     *      {
     *          "orderId": 0,
     *          "alertMsg": "操作成功"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
     * @apiError PayMoneyIdNotFound 未找到报单方式.
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
     * @api {get} v2/new_activity.php 是否有微信支付新活动
     * @apiName actionNewActivity
     * @apiGroup Pay
     * 
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
     *          "msgStyle": "toast",
     *          "alertMsg": "",
     *          "newAcitvityName": "测试新活动名字",
     *          "newAcitvity":
     *          {
     *              "title": "测试新活动",
     *              "url": "http://www.1jiajie.com/"
     *          },
     *          "isShow": "0"
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
     * @api {get} /v2/online_pay_for_member.php 会员余额支付
     * @apiName actionOnlinePayForMember
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id    订单ID.
     * @apiParam {String} money 支付金额.
     * @apiParam {String} telephone 用户电话.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "msgStyle":"toast",
     *          "alertMsg":"\u8ba2\u5355\u5df2\u7ecf\u652f\u4ed8\u8fc7,\u8bf7\u52ff\u91cd\u590d\u63d0\u4ea4"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
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
     * @api {get} v2/wx_app_online_pay.php 微信支付接口
     * @apiName actionWxAppOnline_pay
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} partner_id  商户号.
     * @apiParam {String} app_id  appID.
     * @apiParam {String} order_id  订单ID.
     * @apiParam {String} order_price  订单价格.
     * @apiParam {String} channel  渠道标签.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "old_user_id": "12",
     *          "new_user_id": "3",
     *          "telephone": "18311133170",
     *          "result": "ok",
     *          "retmsg":
     *          {
     *              "appid": "wx865a1a3ff91297df",
     *              "noncestr": "7825b499fcee6fd52682cf032f5e6c8f",
     *              "pack_age": "Sign=WXPay",
     *              "partnerid": "1217983401",
     *              "prepayid": "42010000001509163632a276444d1541",
     *              "sign": "f7fc5f074af8d951ca9e705c18a4b7c768243c66",
     *              "timestamp": 1442390335
     *          },
     *          "msgStyle": "toast",
     *          "alertMsg": ""
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
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
     * @api {get} v2/wx_app_pay.php 会员充值付接口
     * @apiName actionWxAppPay
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} partner_id  商户号.
     * @apiParam {String} app_id  appID.
     * @apiParam {String} order_id  订单ID.
     * @apiParam {String} order_price  订单价格.
     * @apiParam {String} channel  渠道标签.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "old_user_id":"12",
     *          "new_user_id":"3",
     *          "telephone":"18311133170",
     *          "result":"ok",
     *          "retmsg":
     *          {
     *              "appid":"wx865a1a3ff91297df",
     *              "noncestr":"d94008bb02fc2aee5b550bb5befa94f8",
     *              "pack_age":"Sign=WXPay",
     *              "partnerid":"1217983401",
     *              "prepayid":"32010330001510100bd104be573943aa",
     *              "sign":"98498d9f500c1d986034e3c50ef45f5732152cf7",
     *              "timestamp":1444448732
     *          },
     *          "msgStyle":"toast",
     *          "alertMsg":""
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
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
     * @api {get} v2/bfb_app.php 百度钱包支付接口
     * @apiName actionBfbApp
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id    订单ID，不传ID为会员充值.
     * @apiParam {String} order_price    支付金额.
     * @apiParam {String} Partner    商户号.
     * @apiParam {String} channel    渠道标签.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "msgStyle":"toast",
     *          "alertMsg":"",
     *          "data":"service_code=1&sp_no=1500610004&sp_request_type=2&order_create_time=20151010115422&order_no=BAid62385id257&goods_name=e%BC%D2%BD%E0%D4%DA%CF%DF%D6%A7%B8%B6&goods_desc=e%BC%D2%BD%E0%D4%DA%CF%DF%D6%A7%B8%B62.0%D4%AA&total_amount=200¤cy=1&return_url=http%3A%2F%2Ftest.web.1jiajie.com%2Fv2%2Fbfb_notify_url.php&pay_type=2&input_charset=1&version=2&sign_method=1&sign=b72ee079949edee624eb01bb8c686952"
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
     * @api {get} v2/alipay_app.php 支付宝支付接口
     * @apiName actionAlipayApp
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id    订单ID，不传ID为会员支付.
     * @apiParam {String} productName    支付产品名称.
     * @apiParam {String} productDescription    支付产品描述.
     * @apiParam {String} Partner    商户号.
     * @apiParam {String} order_price    支付价格.
     * @apiParam {String} channel    渠道名称.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "msgStyle": "toast",
     *          "alertMsg": "",
     *          "data":"partner=%222088801136967007%22&out_trade_no=%22ALAPP_2015091616182544232_262%22&subject=%22e%E5%AE%B6%E6%B4%81%E5%9C%A8%E7%BA%BF%E6%94%AF%E4%BB%98%22&seller_id=%2247632990%40qq.com%22&body=%22e%E5%AE%B6%E6%B4%81%E5%9C%A8%E7%BA%BF%E6%94%AF%E4%BB%9820.0%E5%85%83%22&total_fee=%2220.0%22¬ify_url=%22http%3A%2F%2Ftest.web.1jiajie.com%2Fv2%2Falipay_notify_url.php%22&service=%22mobile.securitypay.pay%22&payment_type=%221%22&_input_charset=%22utf-8%22"
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
     * @api {get} v2/up_app_pay.php 会员充值银联支付
     * @apiName actionUpAppPay
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} money    支付金额.
     * @apiParam {String} telephone    用户电话.
     * @apiParam {String} channel    渠道名称.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "old_user_id":"12",
     *          "new_user_id":"3",
     *          "telephone":"18311133170",
     *          "result":"ok",
     *          "tn":"201510101256230766118",
     *          "date":
     *          {
     *              "version":"1.0.0",
     *              "charset":"UTF-8",
     *              "transType":"01",
     *              "merId":"898111148990533",
     *              "backEndUrl":"http:\/\/test.web.1jiajie.com\/mobileapinew8\/up_app_pay_notify",
     *              "frontEndUrl":"http:\/\/test.web.1jiajie.com\/mobileapinew8\/up_app_pay_notify",
     *              "orderCurrency":"156",
     *              "orderTime":"20151010125623",
     *              "orderTimeout":"20151010145623",
     *              "orderNumber":"EOUPA20151010125623290642302",
     *              "orderDescription":"E\u5bb6\u6d01\u5145\u503c0.01\u5143",
     *              "orderAmount":"000000000001",
     *              "reqReserved":"
     *              {
     *                  \"start_time\":\"20151010125623\",
     *                  \"id\":\"2302\",
     *                  \"orderNumber\":\"EOUPA20151010125623290642302\",
     *                  \"telephone\":\"18311133170\",\"money\":\"0.01\"
     *              }",
     *              "merReserved":"{test=test}"},
     *              "resp":
     *              {
     *                  "respCode":"00",
     *                  "tn":"201510101256230766118",
     *                  "signMethod":"MD5",
     *                  "transType":"01",
     *                  "charset":"UTF-8",
     *                  "reqReserved":"
     *                  {
     *                      \"start_time\":\"20151010125623\",
     *                      \"id\":\"2302\",
     *                      \"orderNumber\":\"EOUPA20151010125623290642302\",
     *                      \"telephone\":\"18311133170\",
     *                      \"money\":\"0.01\"
     *                  }",
     *                  "signature":"78159fb1b2ed3a8133a08413ceac6747",
     *                  "version":"1.0.0"
     *              },
     *              "msgStyle":"toast",
     *              "alertMsg":""
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
     * @api {get} v2/request_member_card.php 会员充值申请上门支付接口
     * @apiName actionRequestMemberCard
     * @apiGroup Pay
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} card_name 申请的会员卡名称.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "result": "ok",
     *          "msgStyle": "toast",
     *          "alertMsg": "会员卡申请成功，稍后会有客服联系您"
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
     * @api {get} v2/member_card.json  成为会员接口
     * @apiName actionMemberCard
     * @apiGroup Pay
     * 
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
     *          "cardList":
     *          [
     *          {
     *              "cardName": "银　卡:",
     *              "cardCost": "1000",
     *              "moneyBack": "返400券"
     *          },
     *          {
     *              "cardName": "金　卡:",
     *              "cardCost": "3000",
     *              "moneyBack": "返1400券"
     *          },
     *          {
     *              "cardName": "铂金卡:",
     *              "cardCost": "5000",
     *              "moneyBack": "返2600券"
     *          }
     *          ],
     *          "protocolUrl": "http://wap.1jiajie.com/serverinfo/protocol.html",
     *          "buy_cart_way": "0",
     *          "alipay_alert_msg": "您所购买的会员卡金额超过500元手机支付上限，需要先充值到支付宝；或者使用电脑到www.1jiajie.com进行购买",
     *          "membersCoupon":
     *          {
     *              "title": "会员优惠",
     *              "url": "http://wap.1jiajie.com/serverinfo/memberDiscount.html"
     *          }
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