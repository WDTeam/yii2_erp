<?php
namespace api\controllers;

use Yii;
use \core\models\operation\CoreOperationShopDistrictGoods;
use \core\models\operation\CoreOperationCategory;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
use \core\models\operation\coupon\Coupon;
use \core\models\operation\coupon\CouponCode;
use \api\models\LoginCustomer;
class CouponController extends \api\components\Controller
{
    
    /**
     * @api {POST} /coupon/exchange-coupon 兑换优惠劵 （李勇 100%）
     *
     * @apiName ExchangeCoupon
     * @apiGroup coupon
     *
     * @apiParam {String} [customer_phone] 用户手机号
     * @apiParam {String} [coupon_code] 活动码
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
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['coupon_code']) || !intval($param['coupon_code'])||!isset($param['customer_phone']) || !intval($param['customer_phone'])) {
            return $this->send(null, "请填写活动码或手机号", 0, 403);
        }
        $coupon_code = $param['coupon_code'];
        $customer_phone = $param['customer_phone'];
        //验证活动码是否存在
        try{
            $exist_coupon=CouponCode::checkCouponCodeIsAble($coupon_code);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if (!$exist_coupon) {
            return $this->send(null, "优惠码不存在", 0, 403);
        }
        //兑换优惠码
        try{
            $exchange_coupon=CouponCode::generateCouponByCode($customer_phone,$coupon_code);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if ($exchange_coupon) {
            return $this->send($exchange_coupon, "兑换成功", 1);
        } else {
            return $this->send(null, "兑换失败", 0);
        }
    }

    /**
     * @api {Get} /coupon/coupons 获取用户优惠码列表（包括该用户该城市下的优惠码和通用的优惠码） （李勇 80%）
     *
     * @apiName Coupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [city_id]  城市
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg":
     *       "ret": {
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
    public function actionCoupons()
    {

        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "请选择城市", 0, 403);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠码列表
        try{
            $coupons=CouponCustomer::GetCustomerCouponList($checkResult['customer_id'],$city_id);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if (!empty($coupons)) {
            return $this->send($coupons, "获取优惠码列表成功", 1);
        } else {
            return $this->send(null, "优惠码列表为空", 0);
        }
        
    }
    /**
     * @api {Get} /coupon/all-coupons 获取用户全部优惠码列表（包括可用的、不可用的、所有城市的、通用的） （李勇 80%）
     *
     * @apiName AllCoupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [city_id]  城市
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg":
     *       "ret": {
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
    public function actionAllCoupons()
    {

        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        if (!isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "请填写服务或城市名称", 0, 403);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠码列表
        try{
            $coupons=CouponCustomer::GetAllCustomerCouponList($checkResult['customer_id'],$city_id);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if (!empty($coupons)) {
            return $this->send($coupons, "获取优惠码列表成功", 1);
        } else {
            return $this->send(null, "优惠码列表为空", 0);
        }
        
    }
     /**
     * @api {GET} v1/coupon/get-coupon-count 获取用户优惠码数量 （功能已经实现 100%）
     *
     *
     * @apiName GetCouponCount
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取成功"
     *       "ret":{
     *            "couponCount":{
     *            "count":'10'
     *             }
     *          }
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
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录"
     *
     *     }
     *
     */
    public function actionGetCouponCount()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        //检测用户是否登录
        $checkResult = LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }
        try{
            $CouponCount =CouponCustomer::CouponCount($checkResult['customer_id']);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        $ret['couponCount'] = $CouponCount;
        return $this->send($ret, "用户优惠码数量");
    }
}
?>