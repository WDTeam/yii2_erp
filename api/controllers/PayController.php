<?php
namespace api\controllers;

use Yii;
use \api\models\PayParam;
use \core\models\payment\GeneralPay;
use \core\models\customer\CustomerAccessToken;

class PayController extends \api\components\Controller
{


    /**
     * @api {POST} v1/pay/balance-pay 会员余额支付 (赵顺利100%)
     * @apiName actionBalancePay
     * @apiGroup Pay
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} order_id    订单ID.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"支付成功",
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "支付失败"
     *  }
     *
     */
    public function actionBalancePay()
    {
        $params = Yii::$app->request->post() or
        $params = json_decode(Yii::$app->request->rawBody, true);

        if (empty($params['access_token']) || !CustomerAccessToken::checkAccessToken($params['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $customer = CustomerAccessToken::getCustomer($params['access_token']);
        $date=[
            'customer_id'=>$customer->id,
            'order_id'=>$params['order_id'],
        ];

        if(empty(GeneralPay::balancePay($date)))
        {
            return $this->send(null, "支付失败", "error", 403);
        }

        return $this->send(null, "支付成功", "ok");
    }


    /**
     * @api {POST} v1/pay/online-pay 在线支付接口 （已完成）
     * @apiName actionOnlinePay
     * @apiGroup Pay
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} pay_money 支付金额
     * @apiParam {String} channel_id 渠道ID
     *                              1=APP微信,
     *                              2=H5微信,
     *                              3=APP百度钱包,
     *                              4=APP银联,
     *                              5=APP支付宝,
     *                              6=WEB支付宝,
     *                              7=H5百度直达号,
     *                              20=后台支付（未实现）,
     *                              21=微博支付（未实现）,
     * @apiParam {String} [order_id] 订单ID,没有订单号表示充值
     * @apiParam {String} partner 第三方合作号
     *
     * @apiParam {Object} [ext_params] 扩展参数,用于微信/百度直达号（即channel_id=2或7 必填）
     * @apiParam {String} [ext_params.openid] 微信openid （channel_id=2 必填）
     * @apiParam {String} [ext_params.return_url] 同步回调地址 （channel_id=6必填）
     * @apiParam {String} [ext_params.show_url] 显示商品URL（channel_id=6必填）
     * @apiParam {String} [ext_params.customer_name] 商品名称（channel_id=7必填）
     * @apiParam {String} [ext_params.customer_mobile] 用户电话（channel_id=7必填）
     * @apiParam {String} [ext_params.customer_address] 用户地址（channel_id=7必填）
     * @apiParam {String} [ext_params.order_source_url] 订单详情地址（channel_id=7必填）
     * @apiParam {String} [ext_params.page_url] 订单跳转地址（channel_id=7必填）
     * @apiParam {String} [ext_params.goods_name] 订单名称（channel_id=7必填）
     * @apiParam {String} [ext_params.detail] 订单详情 （channel_id=7必填）
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *      "channel_id":"7",
     *      "partner":"1217983401",
     *      "access_token":"00ca52a593ca85ffdb5256372aa642d2",
     *      "pay_money":"0.01",
     *      "order_id":"0",
     *      "ext_params":
     *      {
     *          "openid":"o7Kvajh91Fmh_KYzhwX0LWZtpMPM",
     *          "goods_name":"%E6%B5%8B%E8%AF%95%E5%95%86%E5%93%81",
     *          "customer_name":"%E6%B5%8B%E8%AF%95%E5%95%86%E5%93%81",
     *          "customer_mobile":"18001305711",
     *          "goods_name":"18001305711",
     *          "customer_address":"%E5%8C%97%E4%BA%AC%E7%9C%81",
     *          "order_source_url":"http://www.baidu.com",
     *          "page_url":"http://www.qq.com",
     *          "detail":
     *          [
     *          {
     *              "item_id":"1",
     *              "cat_id":"1",
     *              "name":"寿司",
     *              "desc":"很好吃",
     *              "price":"1"
     *          },
     *          ]
     *      }
     * }
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": 1,
     *      "msg": "数据返回成功",
     *      "ret": {
     *          "sp_no": 1049,
     *          "order_no": "15102301277257",
     *          "total_amount": "1",
     *          "goods_name": "18001305711",
     *          "return_url": "http://127.0.0.1/pay/zhidahao-h5-notify",
     *          "page_url": "http://www.qq.com",
     *          "detail": [
     *          {
     *              "item_id": "1",
     *              "cat_id": "1",
     *              "name": "寿司",
     *              "desc": "很好吃",
     *              "price": "1"
     *          }
     *          ],
     *          "order_source_url": "http://www.baidu.com",
     *          "customer_name": "%E6%B5%8B%E8%AF%95%E5%95%86%E5%93%81",
     *          "customer_mobile": "18001305711",
     *          "customer_address": "%E5%8C%97%E4%BA%AC%E7%9C%81"
     *      }
     *  }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError OrderIdNotFound 未找到订单ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "支付失败"
     *  }
     *
     */

    public function actionOnlinePay()
    {
        $model = new PayParam();
        $name = $model->formName();
        $data[$name] = Yii::$app->request->post() or
        $data[$name] = json_decode(Yii::$app->request->rawBody, true);

        if (empty($data[$name]['access_token']) || !CustomerAccessToken::checkAccessToken($data[$name]['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $customer = CustomerAccessToken::getCustomer($data[$name]['access_token']);

        $data[$name]['customer_id'] = $customer->id;

        $ext_params = [];
        //在线支付（online_pay），在线充值（pay）
        if (empty($data[$name]['order_id'])) {
            if ($data[$name]['channel_id'] == '2') {
                $model->scenario = 'wx_h5_pay';
                $ext_params['openid'] = $data[$name]['ext_params']['openid'];    //微信openid
            } elseif ($data[$name]['channel_id'] == '6') {
                $model->scenario = 'alipay_web_pay';
                $ext_params['return_url'] = $data[$name]['ext_params']['return_url'];    //同步回调地址
                $ext_params['show_url'] = $data[$name]['ext_params']['show_url'];    //显示商品URL
            } elseif ($data[$name]['channel_id'] == '7') {
                $model->scenario = 'zhidahao_h5_pay';
                $ext_params['customer_name'] = $data[$name]['ext_params']['customer_name'];  //商品名称
                $ext_params['customer_mobile'] = $data[$name]['ext_params']['customer_mobile'];  //用户电话
                $ext_params['customer_address'] = $data[$name]['ext_params']['customer_address'];  //用户地址
                $ext_params['order_source_url'] = $data[$name]['ext_params']['order_source_url'];  //订单详情地址
                $ext_params['page_url'] = $data[$name]['ext_params']['page_url'];  //订单跳转地址
                $ext_params['goods_name'] = $data[$name]['ext_params']['goods_name'];  //订单名称
                $ext_params['detail'] = $data[$name]['ext_params']['detail'];  //订单详情
            } else {
                $model->scenario = 'pay';
            }
        } else {
            if ($data[$name]['channel_id'] == '2') {
                $model->scenario = 'wx_h5_online_pay';
                $ext_params['openid'] = $data[$name]['ext_params']['openid'];    //微信openid
            } elseif ($data[$name]['channel_id'] == '6') {
                $model->scenario = 'alipay_web_pay';
                $ext_params['return_url'] = $data[$name]['ext_params']['return_url'];    //同步回调地址
                $ext_params['show_url'] = $data[$name]['ext_params']['show_url'];    //显示商品URL
            } elseif ($data[$name]['channel_id'] == '7') {
                $model->scenario = 'zhidahao_h5_online_pay';
                $ext_params['customer_name'] = $data[$name]['ext_params']['customer_name'];  //商品名称
                $ext_params['customer_mobile'] = $data[$name]['ext_params']['customer_mobile'];  //用户电话
                $ext_params['customer_address'] = $data[$name]['ext_params']['customer_address'];  //用户地址
                $ext_params['order_source_url'] = $data[$name]['ext_params']['order_source_url'];  //订单详情地址
                $ext_params['page_url'] = $data[$name]['ext_params']['page_url'];  //订单跳转地址
                $ext_params['goods_name'] = $data[$name]['ext_params']['goods_name'];  //订单名称
                $ext_params['detail'] = $data[$name]['ext_params']['detail'];  //订单详情
            } else {
                $model->scenario = 'online_pay';
            }
        }

        $data[$name] = array_merge($data[$name], $ext_params);
        $model->attributes = $data[$name];

        if ($model->load($data) && $model->validate()) {
            $retInfo = GeneralPay::getPayParams($model->pay_money, $model->customer_id, $model->channel_id, $model->partner, $model->order_id, $ext_params);
            return $this->send($retInfo['data'], $retInfo['info'], $retInfo['status']);
        }
        return $this->send(null, $model->errors,0);

    }

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

    /**
     * 银联APP回调
     */
    public function actionUpAppNotify()
    {
        $obj = new GeneralPay();
        $obj->UpAppNotify(yii::$app->request->get());
        exit;
    }

    /**
     * 支付宝APP回调
     */
    public function actionAlipayAppNotify()
    {
        $obj = new GeneralPay();
        $obj->alipayAppNotify(yii::$app->request->get());
        exit;
    }

    /**
     * 微信APP回调
     */
    public function actionWxAppNotify()
    {
        $obj = new GeneralPay();
        $obj->wxAppNotify(yii::$app->request->get());
        exit;
    }

    /**
     * 百付宝APP回调
     */
    public function actionBfbAppNotify()
    {
        $obj = new GeneralPay();
        $obj->bfbAppNotify(yii::$app->request->get());
        exit;
    }

    /**
     * 微信H5回调
     */
    public function actionWxH5Notify()
    {
        $obj = new GeneralPay();
        $obj->wxH5Notify(yii::$app->request->get());
        exit;
    }

    /**
     * 直达号H5回调
     */
    public function actionZhidahaoH5Notify()
    {
        $obj = new GeneralPay();
        $obj->zhidahaoH5Notify(yii::$app->request->get());
        exit;
    }

    public function actionTest()
    {
        dump(yii::$app->controller->id);
    }
}

?>
