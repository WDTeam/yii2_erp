<?php
namespace restapi\controllers;

use Yii;
use \core\models\operation\CoreOperationShopDistrictGoods;
use \core\models\operation\CoreOperationCategory;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
use \core\models\operation\coupon\Coupon;
use \core\models\operation\coupon\CouponCode;
use \restapi\models\LoginCustomer;
use \restapi\models\alertMsgEnum;
class CouponController extends \restapi\components\Controller
{
    
    /**
     * @api {POST} /coupon/exchange-coupon 兑换优惠劵 （李勇 100%）
     * 
     * @apiName ExchangeCoupon
     * @apiGroup coupon
     *
     * @apiParam {String} customer_phone 用户手机号
     * @apiParam {String} coupon_code 优惠码
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
     *       "msg": "优惠券不存在，"
     *
     *     }
     */
    public function actionExchangeCoupon()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['coupon_code']) || !intval($param['coupon_code'])||!isset($param['customer_phone']) || !intval($param['customer_phone'])) {
            return $this->send(null, "优惠码或手机号不能为空", 0, 403,null,alertMsgEnum::exchangeCouponDataDefect);
        }
        $coupon_code = $param['coupon_code'];
        $customer_phone = $param['customer_phone'];
        //验证优惠码是否存在
        try{
            $exist_coupon=CouponCode::checkCouponCodeIsAble($coupon_code);
        }catch (\Exception $e) {
            return $this->send($e, "验证优惠码是否存在可用系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if (!$exist_coupon) {
            return $this->send(null, "优惠券不存在", 0, 403,null,alertMsgEnum::exchangeCouponNotExist);
        }
        //兑换优惠码
        try{
            $exchange_coupon=CouponCode::generateCouponByCode($customer_phone,$coupon_code);
        }catch (\Exception $e) {
            return $this->send($e, "兑换优惠券系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if ($exchange_coupon) {
            return $this->send($exchange_coupon, "兑换成功", 1,200,null,alertMsgEnum::exchangeCouponSuccess);
        } else {
            return $this->send(null, "兑换失败", 0,403,null,alertMsgEnum::exchangeCouponFail);
        }
    }

    /**
     * @api {Get} /coupon/coupons 获取用户优惠券列表（包括该用户该城市下的优惠券和通用的优惠券） （李勇 100%）
     *
     * @apiName Coupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_id  城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
     *               "coupon_name": "优惠券名称",
     *                "coupon_price": "优惠券价格",
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
     *       "msg": "优惠券列表为空"
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
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "请选择城市", 0, 403,null,alertMsgEnum::couponsCityNoChoice);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠券列表
        try{
            $coupons=CouponCustomer::GetCustomerCouponList($checkResult['customer_id'],$city_id);
        }catch (\Exception $e) {
            return $this->send($e, "获取用户优惠券列表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if (!empty($coupons)) {
            return $this->send($coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 403,null,alertMsgEnum::couponsFail);
        }
        
    }
    /**
     * @api {Get} /coupon/all-coupons 获取用户全部优惠券列表（包括可用的、不可用的、所有城市的、通用的） （李勇 100%）
     *
     * @apiName AllCoupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
     *       "msg": "优惠券列表为空"
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
            return $this->send(null,$checkResult['msg'], 0, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        //获取该用户所有的优惠码列表
        try{
           $coupons=CouponCustomer::GetAllCustomerCouponList($checkResult['customer_id']);
        }catch (\Exception $e) {
            return $this->send(null, "获取用户所有的优惠码列表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if (!empty($coupons)) {
            return $this->send($coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::allCouponsSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0,403,null,alertMsgEnum::allCouponsFail);
        }
        
    }
    
     /**
     * @api {Get} /coupon/coupons-over-due 获取用户优惠券列表（包括该城市可用的、还有过期30天内的优惠券） （李勇 100%）
     *
     * @apiName CouponsOverDue
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_id  城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
     *       "msg": "优惠券列表为空"
     *
     *     }
     *
     */
    public function actionCouponsOverDue()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "城市id不能为空", 0, 403,null,alertMsgEnum::couponsOverDueNoChoice);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠券列表
        try{
            $coupons=CouponCustomer::GetCustomerDueCouponList($checkResult['customer_id'],$city_id);
        }catch (\Exception $e) {
            return $this->send($e, "获取用户优惠券列表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if (!empty($coupons)) {
            return $this->send($coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsOverDueSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 403,null,alertMsgEnum::couponsOverDueFail);
        }
        
    }
    
     /**
     * @api {GET} v1/coupon/get-coupon-count 获取用户优惠券数量 （功能已经实现 100%）
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
            return $this->send(null, $checkResult['msg'],0, 403,null,alertMsgEnum::customerLoginFailed);
        }
        try{
            $CouponCount =CouponCustomer::CouponCount($checkResult['customer_id']);
        }catch (\Exception $e) {
            return $this->send($e, "获取用户优惠券数量系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        $ret['couponCount'] = $CouponCount;
        return $this->send($ret, "获取用户优惠券数量成功", 1, 200,null,alertMsgEnum::getCouponCountSuccess);
    }
}
?>