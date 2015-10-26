<?php
namespace api\controllers;

use Yii;
use core\models\Operation\CoreOperationShopDistrictGoods;
use core\models\Operation\CoreOperationCategory;
use \core\models\customer\CustomerAccessToken;

class CouponController extends \api\components\Controller
{
    /**
     * @api {POST} /user/exchange-coupon 兑换优惠劵 （李勇 80%）
     *
     * @apiName ExchangeCoupon
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [city] 城市
     * @apiParam {String} [coupon_code] 优惠码
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "兑换成功",
     *       "ret":{
     *           "id":1,
     *           "coupon_id":1,
     *           "coupon_name":"优惠券名称",
     *           "coupon_price":123
     *      }
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
     * @apiError CouponNotFound 优惠码不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "优惠码不存在，"
     *
     *     }
     */
    public function actionExchangeCoupon()
    {
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!CustomerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        if(!isset($param['city'])|| !intval($param['city'])){
            return $this->send(null, "请选择城市", 0, 403);
        }
        if(!isset($param['coupon_code'])|| !intval($param['coupon_code'])){
            return $this->send(null, "请填写优惠码或邀请码", 0, 403);
        }
        $city=$param['city'];
        $coupon_code=$param['coupon_code'];
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        $customer_id= $customer->id;
        //验证优惠码是否存在
        //$exist_coupon=CouponCustomer::existCoupon($city,$coupon_code);
        $exist_coupon=1;
        if(!$exist_coupon){
            return $this->send(null, "优惠码不存在", 0, 403);
        }
        //兑换优惠码
        // $exchange_coupon=CouponCustomer::exchangeCoupon($city,$coupon_code,$customer_id);
        $exchange_coupon=[
            "id" => 1,
            "coupon_id" => 2,
            "coupon_name" => "优惠券名称",
            "coupon_price" => 123
        ];
        if($exchange_coupon){
            return $this->send($exchange_coupon, "兑换成功", 1);
        }else{
            return $this->send(null, "兑换失败", 0);
        }
    }

    /**
     * @api {Get} /user/coupons获取用户优惠码或同城市 （李勇 80%）
     *
     * @apiName GetCouponCustomer
     * @apiGroup Coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [good_type]
     * @apiParam {String} [city_name]  城市
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": {
     *           "coupon":[
     *             {
     *               "id": "1",
     *               "coupon_name": "优惠码名称",
     *                "coupon_price": "优惠码价格",
     *                "coupon_type_name": "优惠券类型名称",
     *                "coupon_service_type_id": "服务类别id",
     *                "coupon_service_type_name": "服务类别名称",
     *               }
     *            ]
     *           }
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
     *  *     {
     *       "code": "0",
     *       "msg": "优惠码列表为空"
     *
     *     }
     *
     */
    public function actionGetCouponCustomer()
    {

        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            /**
             * 获取改用户city_name下面,所有的优惠券
             */
            if (!empty($param['city_name']) && $param['coupon_type'] == 1) {

                $CouponData = CouponCustomer::getCouponCustomer($customer->id);

                if (!empty($CouponData)) {
                    $ret = array();
                    foreach ($CouponData as $key => $val) {
                        $Coupon = \core\models\coupon\Coupon::getCoupon($val['coupon_id'], $param['city_name']);
                        foreach ($Coupon as $key => $val) {
                            $ret['coupon'][] = $val;
                        }
                    }

                    return $this->send($ret, $param['city_name'] . "优惠码列表");
                } else {
                    return $this->send([1], "规定城市优惠码列表为空", "0");
                }
            }

            /**
             * 返回全国范围内的优惠码
             */
            if (empty($param['city_name']) && $param['coupon_type'] == 1) {
                $CouponData = CouponCustomer::getCouponCustomer($customer->id, 1);
                $ret['couponCustomer'] = $CouponData;
                return $this->send($ret, "全国范围优惠码列表", "1");
            }

            /**
             * 返回规定城市和全国范围内的优惠码
             */
            if (@$param['city_name'] && $param['coupon_type'] == 2) {

                $CouponData = CouponCustomer::getCouponCustomer($customer->id);

                if (!empty($CouponData)) {
                    $ret = array();
                    foreach ($CouponData as $key => $val) {
                        $Coupon = Coupon::getCoupon($val['coupon_id'], $param['city_name']);
                        foreach ($Coupon as $key => $val) {
                            $ret['coupon'][] = $val;
                        }
                    }
                    #return $this->send($ret, $param['city_name'] . "优惠码列表", "1");
                }

                $CouponCount =CouponCustomer::getCouponCustomer($customer->id, 1);
                $ret['couponCustomer'][] = $CouponCount;

                return $this->send($ret, '城市' . $param['city_name'] . "优惠码和全国优惠码列表", "1");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", "0", 403);
            }
        } else {

            return $this->send(null, "用户认证已经过期,请重新登录1", "0", 403);
        }
    }
}
?>