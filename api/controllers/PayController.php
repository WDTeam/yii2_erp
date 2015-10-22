<?php
namespace api\controllers;

use Yii;
use \api\models\PayParam;
use \core\models\GeneralPay\GeneralPay;

class PayController extends \api\components\Controller
{


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
     * @api {post} pay/pay 支付接口 （已完成）
     * @apiName actionPay
     * @apiGroup Pay
     *
     * @apiParam integer pay_money 支付金额
     * @apiParam integer customer_id 消费者ID
     * @apiParam integer channel_id 渠道ID
     *                              1=APP微信,
     *                              2=H5微信,
     *                              3=APP百度钱包,
     *                              4=APP银联,
     *                              5=APP支付宝,
     *                              6=WEB支付宝,
     *                              7=H5百度直达号,
     *                              20=后台支付,(未实现)
     *                              21=微博支付,（未实现）
     * @apiParam integer [order_id] 订单ID,没有订单号表示充值
     * @apiParam integer partner 第三方合作号
     *
     * @apiParam object [ext_params] 扩展参数,用于微信/百度直达号（即channel_id=2或4 必填）
     * @apiParam string [ext_params.openid] （channel_id=2 必填）
     * @apiParam string [ext_params.customer_name]
     * @apiParam string [ext_params.customer_mobile]
     * @apiParam string [ext_params.customer_address]
     * @apiParam string [ext_params.order_source_url]
     * @apiParam string [ext_params.page_url]
     * @apiParam string [ext_params.goods_name]
     * @apiParam string [ext_params.detail]
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   code: "ok",
     *   msg: "操作成功",
     *   ret: {
     *           appId: "wx7558e67c2d61eb8f",
     *           nonceStr: "bw49b49oypsepjwu72rxr6vm2l1w2yrh",
     *           package: "prepay_id=wx2015102019101737c5eba0520251793495",
     *           signType: "MD5",
     *           timeStamp: "1445339417",
     *           paySign: "6C4C398E98ACB00DAD672098C71DB4F2"
     *      }
     *   }
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

    public function actionPay()
    {
        $model = new PayParam();
        $name = $model->formName();
        $data[$name] = Yii::$app->request->post();
        $ext_params = [];
        //在线支付（online_pay），在线充值（pay）
        if (empty($data[$name]['order_id'])) {
            if ($data[$name]['channel_id'] == '2') {
                $model->scenario = 'wx_h5_pay';
                $ext_params['openid'] = $data[$name]['ext_params']['openid'];    //微信openid
            } elseif ($data[$name]['channel_id'] == '7') {
                $model->scenario = 'zhidahao_h5_pay';
                $ext_params['customer_name'] = $data[$name]['ext_params']['customer_name'];  //商品名称
                $ext_params['customer_mobile'] = $data[$name]['ext_params']['customer_mobile'];  //用户电话
                $ext_params['customer_address'] = $data[$name]['ext_params']['customer_address'];  //用户地址
                $ext_params['order_source_url'] = $data[$name]['ext_params']['order_source_url'];  //订单详情地址
                $ext_params['page_url'] = $data[$name]['ext_params']['page_url'];  //订单跳转地址
                $ext_params['goods_name'] = $data[$name]['ext_params']['goods_name'];  //订单跳转地址
                $ext_params['detail'] = $data[$name]['ext_params']['detail'];  //订单详情
            } else {
                $model->scenario = 'pay';
            }
        } else {
            if ($data[$name]['channel_id'] == '2') {
                $model->scenario = 'wx_h5_online_pay';
                $ext_params['openid'] = $data[$name]['ext_params']['openid'];    //微信openid
            } elseif ($data[$name]['channel_id'] == '7') {
                $model->scenario = 'zhidahao_h5_online_pay';
                $ext_params['customer_name'] = $data[$name]['ext_params']['customer_name'];  //商品名称
                $ext_params['customer_mobile'] = $data[$name]['ext_params']['customer_mobile'];  //用户电话
                $ext_params['customer_address'] = $data[$name]['ext_params']['customer_address'];  //用户地址
                $ext_params['order_source_url'] = $data[$name]['ext_params']['order_source_url'];  //订单详情地址
                $ext_params['page_url'] = $data[$name]['ext_params']['page_url'];  //订单跳转地址
                $ext_params['goods_name'] = $data[$name]['ext_params']['goods_name'];  //订单跳转地址
                $ext_params['detail'] = $data[$name]['ext_params']['detail'];  //订单详情
            } else {
                $model->scenario = 'online_pay';
            }
        }

        $data[$name] = array_merge($data[$name], $ext_params);
        $model->attributes = $data[$name];

        if ($model->load($data) && $model->validate()) {
            $retInfo = GeneralPay::getPayParams($model->pay_money, $model->customer_id, $model->channel_id, $model->partner, $model->order_id, $ext_params);
            return $this->send($retInfo['data']);
        }
        return $this->send(null, $model->errors, "error");

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