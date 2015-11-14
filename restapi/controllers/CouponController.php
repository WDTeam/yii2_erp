<?php
namespace restapi\controllers;

use Yii;
use \core\models\operation\CoreOperationShopDistrictGoods;
use \core\models\operation\CoreOperationCategory;
use \core\models\customer\CustomerAccessToken;
use core\models\operation\coupon\CouponUserinfo;
use \restapi\models\LoginCustomer;
use \restapi\models\alertMsgEnum;
class CouponController extends \restapi\components\Controller
{
    
    /**
     * @api {POST} /coupon/exchange-coupon [POST] /coupon/exchange-coupon（100%）
     * 
     * @apiDescription 兑换优惠劵（李勇）
     * @apiName actionExchangeCoupon
     * @apiGroup coupon
     *
     * @apiParam {String} customer_phone 用户手机号
     * @apiParam {String} coupon_code 优惠码
     * @apiParam {String} order_channel_name 订单渠道名称
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *    {
     *           "code": 1,
     *           "msg": "兑换成功",
     *           "ret": {
     *               "is_status": 1,
     *               "msg": "数据库写入成功",
     *               "data": {
     *                   "id": "优惠券id",
     *                   "couponrule_price": "优惠券金额",
     *                   "couponrule_name": "优惠券名称",
     *                   "couponrule_use_start_time": "优惠券的用户可使用的开始时间",
     *                   "couponrule_use_end_time": "优惠券的用户可使用的结束时间",
     *                   "couponrule_service_type_id": "服务类别id"
     *               }
     *           },
     *           "alertMsg": "兑换成功"
     *       }
     * 
     * @apiError CouponNotFound 优惠码不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *    {
     *       "code": 0,
     *       "msg": "优惠码不存在",
     *       "ret": {},
     *       "alertMsg": "兑换失败"
     *     }
     */
    public function actionExchangeCoupon()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['coupon_code']) || !$param['coupon_code']||!isset($param['customer_phone']) || !$param['customer_phone']) {
            return $this->send(null, "优惠码或手机号不能为空", 0, 403,null,alertMsgEnum::exchangeCouponDataDefect);
        }
        $coupon_code = $param['coupon_code'];
        $customer_phone = $param['customer_phone'];
       //兑换优惠码
//        try{
            $exchange_coupon=CouponUserinfo::generateCouponByCode($customer_phone,$coupon_code);
//        }catch (\Exception $e) {
//            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::bossError);
//        }
        if($exchange_coupon["is_status"]==4011){
            //此手机号未被注册
            return $this->send(null, "此手机号未被注册,请您先注册登录后再兑换优惠券。", 0,403,null,alertMsgEnum::exchangeCouponNoCustomer);
        }elseif($exchange_coupon["is_status"]==4012){
            //优惠码已经被领取或使用
            return $this->send(null, "优惠码已经被领取或使用", 0,403,null,alertMsgEnum::exchangeCouponIsUsed);
        }elseif($exchange_coupon["is_status"]==4013){
            //优惠券不存在
            return $this->send(null, "优惠券不存在", 0,403,null,alertMsgEnum::exchangeCouponNotExist);
        }elseif($exchange_coupon["is_status"]==4014){
            //优惠券不可用
            return $this->send(null, "优惠券不可用", 0,403,null,alertMsgEnum::exchangeCouponUnuse);
        }elseif($exchange_coupon["is_status"]==4015){
            //优惠券兑换时间已过期
            return $this->send(null, "优惠券兑换时间已过期", 0,403,null,alertMsgEnum::exchangeCouponIsOver);
        }elseif($exchange_coupon["is_status"]==4016){
            //优惠券已删除
            return $this->send(null, "优惠券已删除", 0,403,null,alertMsgEnum::exchangeCouponFailIsdel);
        }elseif($exchange_coupon["is_status"]==4017){
            //优惠券已禁用
            return $this->send(null, "优惠券已禁用", 0,403,null,alertMsgEnum::exchangeCouponDisable);
        }elseif($exchange_coupon["is_status"]==4018){
            //数据库写入失败
            return $this->send(null, "数据库写入失败", 0,403,null,alertMsgEnum::exchangeCouponFail);
        }elseif($exchange_coupon["is_status"]==4020){
            //输入的优惠码有误
            return $this->send(null, "输入的优惠码有误", 0,403,null,alertMsgEnum::exchangeCouponErrorCoupon);
        }elseif($exchange_coupon["is_status"]==1){
            //兑换成功
            return $this->send($exchange_coupon, "兑换成功", 1,200,null,alertMsgEnum::exchangeCouponSuccess);
        }
    }

    /**
     * @api {GET} /coupon/coupons [GET] /coupon/coupons（100%）
     *
     * @apiDescription  下单时获取用户优惠券列表（包括该用户该城市下的优惠券和通用的优惠券）（李勇）
     * @apiName actionCoupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {Strimg} service_type_id 服务类别id
     * @apiParam {Strimg} good_type_id 商品类别id
     * @apiParam {String} city_id  城市
     * @apiParam {String} order_channel_name 订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "获取优惠券列表成功",
     *       "ret": [
     *           {
     *               "id": "优惠券id",
     *               "coupon_userinfo_name": "优惠券名称",
     *               "coupon_userinfo_price": "优惠券价值",
     *               "couponrule_use_start_time": "优惠券的用户可使用的开始时间",
     *               "couponrule_use_end_time": "过期时间",
     *               "couponrule_type": "实收金额优惠券类型1为全网优惠券2为类别优惠券3为商品优惠券",
     *               "couponrule_service_type_id": "服务类别id",
     *               "couponrule_commodity_id": "如果是商品优惠券id"
     *           }
     *       ],
     *       "alertMsg": "获取优惠券列表成功"
     *   }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *        }
     *
     */
    public function actionCoupons()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 401, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "请选择城市", 0, 403,null,alertMsgEnum::couponsCityNoChoice);
        }
        if ( !isset($param['service_type_id']) || !$param['service_type_id']) {
            return $this->send(null, "请选择服务类别id", 0, 403,null,alertMsgEnum::couponsCityNoService);
        } 
        if ( !isset($param['good_type_id']) || !$param['good_type_id']) {
            return $this->send(null, "请选择商品类别id", 0, 403,null,alertMsgEnum::couponsCityNoGood);
        }
        $city_id = $param['city_id'];
        $service_type_id = $param['service_type_id'];
        $good_type_id = $param['good_type_id'];
        //获取该用户该城市的优惠券列表
        try{
            $coupons=CouponUserinfo::GetCustomerCouponList($checkResult['customer_phone'],$city_id,$service_type_id,$good_type_id);
        }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::bossError);
        }
        if ($coupons["is_status"]==1) {
            return $this->send($coupons["data"], "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 403,null,alertMsgEnum::couponsFail);
        }
        
    }
     /**
     * @api {GET} /coupon/coupons-over-due  [GET] /coupon/coupons-over-due（100%）
     *
     * @apiDescription 获取用户优惠券列表（包括该城市可用的、还有过期30天内的优惠券）（李勇）
     * @apiName actionCouponsOverDue
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_id  城市
     * @apiParam {String} order_channel_name 订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "获取优惠券列表成功",
     *       "ret": [
     *           {
     *               "id": "优惠券id",
     *               "coupon_userinfo_name": "优惠券名称",
     *               "coupon_userinfo_price": "优惠券价值",
     *               "couponrule_use_start_time": "优惠券的用户可使用的开始时间",
     *               "couponrule_use_end_time": "过期时间",
     *               "couponrule_type": "实收金额优惠券类型1为全网优惠券2为类别优惠券3为商品优惠券",
     *               "couponrule_service_type_id": "服务类别id",
     *               "couponrule_commodity_id": "如果是商品优惠券id"
     *           }
     *       ],
     *       "alertMsg": "获取优惠券列表成功"
     *   }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *      }
     *
     */
    public function actionCouponsOverDue()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 401, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "城市id不能为空", 0, 403,null,alertMsgEnum::couponsOverDueNoChoice);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠券列表
        try{
            $coupons=CouponUserinfo::GetCustomerDueCouponList($checkResult['customer_phone'],$city_id);
        }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::bossError);
        }
        if ($coupons["is_status"]==1) {
            return $this->send($coupons["data"], "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsOverDueSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 403,null,alertMsgEnum::couponsOverDueFail);
        }
        
    }
    
     /**
     * @api {GET} /coupon/get-coupon-count {GET} /coupon/get-coupon-count（100%）
     *
     * @apiDescription 获取用户优惠券数量（李勇）
     * @apiName actionGetCouponCount
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} order_channel_name 订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *          "code": 1,
     *          "msg": "获取用户优惠券数量成功",
     *          "ret": {
     *              "couponCount": "优惠券数量"
     *          },
     *          "alertMsg": "获取用户优惠券数量成功"
     *       }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *      }
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
            return $this->send(null, $checkResult['msg'],401, 403,null,alertMsgEnum::customerLoginFailed);
        }
        try{
            $CouponCount=CouponUserinfo::CouponCount($checkResult['customer_phone']);
        }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::bossError);
        }
        if($CouponCount["is_status"]==1){
            $ret['couponCount'] = $CouponCount["data"];
            return $this->send($ret, "获取用户优惠券数量成功", 1, 200,null,alertMsgEnum::getCouponCountSuccess);
        }else{
            return $this->send(null, "获取用户优惠券数量失败", 0, 403,null,alertMsgEnum::getCouponCountFail);
        }
        
    }
    
    /**
     * @api {GET} /coupon/get-customer-coupon-total {GET} /coupon/get-customer-coupon-total（100%）
     *
     * @apiDescription 获取用户优惠券总额（李勇）
     * @apiName actionGetCustomerCouponTotal
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_id  城市
     * @apiParam {String} order_channel_name 订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "code": 1,
     *           "msg": "获取用户优惠券总额成功",
     *           "ret": {
     *               "couponTotal": "30.00"
     *           },
     *           "alertMsg": "获取用户优惠券总额成功"
     *       }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *      }
     *
     */
    public function actionGetCustomerCouponTotal()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 401, 403,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "城市id不能为空", 0, 403,null,alertMsgEnum::couponsOverDueNoChoice);
        }
        $city_id = $param['city_id'];
        try{
            $CouponTotal=CouponUserinfo::GetCustomerCouponTotal($checkResult['customer_phone'],$city_id);
        }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::bossError);
        }
        if($CouponTotal["is_status"]==1){
            $ret['couponTotal'] = $CouponTotal["data"];
            return $this->send($ret, "获取用户优惠券总额成功", 1, 200,null,alertMsgEnum::getCustomerCouponTotalSuccess);
        }else{
            return $this->send(null, "获取用户优惠券总额失败", 0, 403,null,alertMsgEnum::getCustomerCouponTotalFail);
        }
        
    }
}
?>