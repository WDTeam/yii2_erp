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
     * @api {POST} /coupon/exchange-coupon [POST] /coupon/exchange-coupon（100%）
     * 
     * @apiDescription 兑换优惠劵（李勇）
     * @apiName actionExchangeCoupon
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
     *       "code": 1,
     *       "msg": "兑换成功",
     *       "ret": {
     *           "customer_id": 1,
     *           "coupon_id": 5,
     *           "coupon_code_id": 10,
     *           "coupon_code": "fkdaibz0",
     *           "coupon_name": "万能优惠券",
     *           "coupon_price": "10.00",
     *           "expirate_at": 1455206400,
     *           "is_used": 0,
     *           "created_at": 1446605320,
     *           "updated_at": 1446605320,
     *           "id": 10
     *       },
     *       "alertMsg": "兑换成功"
     *     }
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
            return $this->send(null, "优惠码或手机号不能为空", 0, 200,null,alertMsgEnum::exchangeCouponDataDefect);
        }
        $coupon_code = $param['coupon_code'];
        $customer_phone = $param['customer_phone'];
        //验证优惠码是否存在
        try{
            $exist_coupon=CouponCode::checkCouponCodeIsAble($coupon_code);
        }catch (\Exception $e) {
            return $this->send($e, "验证优惠码是否存在可用系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        if (!$exist_coupon) {
            return $this->send(null, "优惠码不存在", 0, 200,null,alertMsgEnum::exchangeCouponFail);
        }
        //验证优惠码是否已经绑定用户
        try{
            $coupon_is_used=CouponCustomer::checkCouponIsUsed($coupon_code);
        }catch (\Exception $e) {
            return $this->send($e, "验证优惠码是否已经绑定用户系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        if ($coupon_is_used) {
            return $this->send(null, "优惠券已经绑定用户", 0, 200,null,alertMsgEnum::exchangeCouponFail);
        }
        //兑换优惠码
        try{
            $exchange_coupon=CouponCode::generateCouponByCode($customer_phone,$coupon_code);
        }catch (\Exception $e) {
            return $this->send($e, "兑换优惠券系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        if ($exchange_coupon) {
            unset($exchange_coupon['is_del']);
            return $this->send($exchange_coupon, "兑换成功", 1,200,null,alertMsgEnum::exchangeCouponSuccess);
        } else {
            return $this->send(null, "兑换失败", 0,200,null,alertMsgEnum::exchangeCouponFail);
        }
    }

    /**
     * @api {Get} /coupon/coupons Coupons（100%）
     *
     * @apiDescription  下单时获取用户优惠券列表（包括该用户该城市下的优惠券和通用的优惠券）（李勇）
     * @apiName actionCoupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {Strimg} service_type_id 服务类别id
     * @apiParam {String} city_id  城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 1,
     *   "msg": "获取优惠券列表成功",
     *   "ret": [
     *       {
     *           "id": "优惠券id",
     *           "coupon_name": "优惠券名称",
     *           "coupon_price": "优惠券价值",
     *           "coupon_category": "优惠券范畴0为一般优惠券1为赔付优惠券",
     *           "coupon_category_name": "优惠券范畴",
     *           "coupon_type": "优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券",
     *           "coupon_type_name": "优惠券类型名称",
     *           "coupon_service_type_id": "服务类别id",
     *           "coupon_service_type_name": "服务类别名称",
     *           "coupon_service_id": "服务id",
     *           "coupon_service_name": "服务名称",
     *           "coupon_city_limit": "城市限制0为不限1为单一城市限制",
     *           "coupon_city_id": "关联城市",
     *           "coupon_city_name": "城市名称",
     *           "coupon_customer_type": "适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员",
     *           "coupon_customer_type_name": "适用客户类别名称",
     *           "coupon_time_type": "优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算",
     *           "coupon_time_type_name": "优惠券有效时间类型名称",
     *           "coupon_begin_at": "开始时间",
     *           "coupon_end_at": "领取时间和使用时间一致时的结束时间(过期时间以这个为准)",
     *           "coupon_get_end_at": "领取时间和使用时间不一致时的领取结束时间",
     *           "coupon_use_end_days": "领取时间和使用时间不一致时过期天数",
     *           "coupon_promote_type": "优惠券优惠类型0为立减1为满减2为每减",
     *           "coupon_promote_type_name": "优惠券优惠类型名称",
     *           "coupon_order_min_price": "满减或每减时订单最小金额",
     *           "coupon_code_num": "优惠码个数",
     *           "coupon_code_max_customer_num": "单个优惠码最大使用人数",
     *           "is_disabled": "是否禁用",
     *           "created_at": "创建时间",
     *           "updated_at": "更新时间",
     *           "system_user_id": "系统用户id",
     *           "system_user_name": "系统用户名称",
     *           "customer_id": "客户id",
     *           "coupon_id": "优惠规则id",
     *           "coupon_code_id": "优惠码id",
     *           "coupon_code": "优惠码",
     *           "expirate_at": "过期时间",
     *           "is_used": "是否已经使用"
     *       }
     *     ]
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
            return $this->send(null, $checkResult['msg'], 0, 200,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "请选择城市", 0, 200,null,alertMsgEnum::couponsCityNoChoice);
        }
        if ( !isset($param['service_type_id']) || !$param['service_type_id']) {
            return $this->send(null, "请选择服务类别id", 0, 200,null,alertMsgEnum::couponsCityNoService);
        }
        $city_id = $param['city_id'];
        $service_type_id = $param['service_type_id'];
        //获取该用户该城市的优惠券列表
//        try{
            $coupons=CouponCustomer::GetCustomerCouponList($checkResult['customer_id'],$city_id,$service_type_id);
//        }catch (\Exception $e) {
//            return $this->send($e, "获取用户优惠券列表系统错误", 1024, 200,null,alertMsgEnum::bossError);
//        }
        if (!empty($coupons)) {
            $arr_coupons=array();
            foreach($coupons as $coupon){
                unset($coupon["is_del"]);
                $arr_coupons[]=$coupon;
            }
            return $this->send($arr_coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 200,null,alertMsgEnum::couponsFail);
        }
        
    }
    /**
     * @api {Get} /coupon/all-coupons [Get] /coupon/all-coupons（ 100%）
     *
     * @apiDescription   获取用户全部优惠券列表（包括可用的、不可用的、所有城市的、通用的）（李勇）
     * @apiName actionAllCoupons
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 1,
     *   "msg": "获取优惠券列表成功",
     *   "ret": [
     *       {
     *           "id": "优惠券id",
     *           "coupon_name": "优惠券名称",
     *           "coupon_price": "优惠券价值",
     *           "coupon_category": "优惠券范畴0为一般优惠券1为赔付优惠券",
     *           "coupon_category_name": "优惠券范畴",
     *           "coupon_type": "优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券",
     *           "coupon_type_name": "优惠券类型名称",
     *           "coupon_service_type_id": "服务类别id",
     *           "coupon_service_type_name": "服务类别名称",
     *           "coupon_service_id": "服务id",
     *           "coupon_service_name": "服务名称",
     *           "coupon_city_limit": "城市限制0为不限1为单一城市限制",
     *           "coupon_city_id": "关联城市",
     *           "coupon_city_name": "城市名称",
     *           "coupon_customer_type": "适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员",
     *           "coupon_customer_type_name": "适用客户类别名称",
     *           "coupon_time_type": "优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算",
     *           "coupon_time_type_name": "优惠券有效时间类型名称",
     *           "coupon_begin_at": "开始时间",
     *           "coupon_end_at": "领取时间和使用时间一致时的结束时间(过期时间以这个为准)",
     *           "coupon_get_end_at": "领取时间和使用时间不一致时的领取结束时间",
     *           "coupon_use_end_days": "领取时间和使用时间不一致时过期天数",
     *           "coupon_promote_type": "优惠券优惠类型0为立减1为满减2为每减",
     *           "coupon_promote_type_name": "优惠券优惠类型名称",
     *           "coupon_order_min_price": "满减或每减时订单最小金额",
     *           "coupon_code_num": "优惠码个数",
     *           "coupon_code_max_customer_num": "单个优惠码最大使用人数",
     *           "is_disabled": "是否禁用",
     *           "created_at": "创建时间",
     *           "updated_at": "更新时间",
     *           "system_user_id": "系统用户id",
     *           "system_user_name": "系统用户名称",
     *           "customer_id": "客户id",
     *           "coupon_id": "优惠规则id",
     *           "coupon_code_id": "优惠码id",
     *           "coupon_code": "优惠码",
     *           "expirate_at": "过期时间",
     *           "is_used": "是否已经使用"
     *       }
     *     ]
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
    public function actionAllCoupons()
    {

        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测用户是否登录
        $checkResult =LoginCustomer::checkCustomerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null,$checkResult['msg'], 0, 200,null,alertMsgEnum::customerLoginFailed);
        } 
        //获取该用户所有的优惠码列表
        try{
           $coupons=CouponCustomer::GetAllCustomerCouponList($checkResult['customer_id']);
        }catch (\Exception $e) {
            return $this->send(null, "获取用户所有的优惠码列表系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        if (!empty($coupons)) {
            $arr_coupons=array();
            foreach($coupons as $coupon){
                unset($coupon["is_del"]);
                $arr_coupons[]=$coupon;
            }
            return $this->send($arr_coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::allCouponsSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0,200,null,alertMsgEnum::allCouponsFail);
        }
        
    }
    
     /**
     * @api {Get} /coupon/coupons-over-due  Coupons-Over-Due（100%）
     *
     * @apiDescription 获取用户优惠券列表（包括该城市可用的、还有过期30天内的优惠券）（李勇）
     * @apiName actionCouponsOverDue
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_id  城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *   "code": 1,
     *   "msg": "获取优惠券列表成功",
     *   "ret": [
     *       {
     *           "id": "优惠券id",
     *           "coupon_name": "优惠券名称",
     *           "coupon_price": "优惠券价值",
     *           "coupon_category": "优惠券范畴0为一般优惠券1为赔付优惠券",
     *           "coupon_category_name": "优惠券范畴",
     *           "coupon_type": "优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券",
     *           "coupon_type_name": "优惠券类型名称",
     *           "coupon_service_type_id": "服务类别id",
     *           "coupon_service_type_name": "服务类别名称",
     *           "coupon_service_id": "服务id",
     *           "coupon_service_name": "服务名称",
     *           "coupon_city_limit": "城市限制0为不限1为单一城市限制",
     *           "coupon_city_id": "关联城市",
     *           "coupon_city_name": "城市名称",
     *           "coupon_customer_type": "适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员",
     *           "coupon_customer_type_name": "适用客户类别名称",
     *           "coupon_time_type": "优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算",
     *           "coupon_time_type_name": "优惠券有效时间类型名称",
     *           "coupon_begin_at": "开始时间",
     *           "coupon_end_at": "领取时间和使用时间一致时的结束时间(过期时间以这个为准)",
     *           "coupon_get_end_at": "领取时间和使用时间不一致时的领取结束时间",
     *           "coupon_use_end_days": "领取时间和使用时间不一致时过期天数",
     *           "coupon_promote_type": "优惠券优惠类型0为立减1为满减2为每减",
     *           "coupon_promote_type_name": "优惠券优惠类型名称",
     *           "coupon_order_min_price": "满减或每减时订单最小金额",
     *           "coupon_code_num": "优惠码个数",
     *           "coupon_code_max_customer_num": "单个优惠码最大使用人数",
     *           "is_disabled": "是否禁用",
     *           "created_at": "创建时间",
     *           "updated_at": "更新时间",
     *           "system_user_id": "系统用户id",
     *           "system_user_name": "系统用户名称",
     *           "customer_id": "客户id",
     *           "coupon_id": "优惠规则id",
     *           "coupon_code_id": "优惠码id",
     *           "coupon_code": "优惠码",
     *           "expirate_at": "过期时间",
     *           "is_used": "是否已经使用"
     *       }
     *     ]
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
            return $this->send(null, $checkResult['msg'], 0, 200,null,alertMsgEnum::customerLoginFailed);
        } 
        if ( !isset($param['city_id']) || !$param['city_id']) {
            return $this->send(null, "城市id不能为空", 0, 200,null,alertMsgEnum::couponsOverDueNoChoice);
        }
        $city_id = $param['city_id'];
        //获取该用户该城市的优惠券列表
        try{
            $coupons=CouponCustomer::GetCustomerDueCouponList($checkResult['customer_id'],$city_id);
        }catch (\Exception $e) {
            return $this->send($e, "获取用户优惠券列表系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        if (!empty($coupons)) {
            $arr_coupons=array();
            foreach($coupons as $coupon){
                unset($coupon["is_del"]);
                $arr_coupons[]=$coupon;
            }
            return $this->send($arr_coupons, "获取优惠券列表成功", 1, 200,null,alertMsgEnum::couponsOverDueSuccess);
        } else {
            return $this->send(null, "优惠券列表为空", 0, 200,null,alertMsgEnum::couponsOverDueFail);
        }
        
    }
    
     /**
     * @api {GET} /coupon/get-coupon-count Get-Coupon-Count（100%）
     *
     * @apiDescription 获取用户优惠券数量（李勇）
     * @apiName actionGetCouponCount
     * @apiGroup coupon
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
            return $this->send(null, $checkResult['msg'],0, 200,null,alertMsgEnum::customerLoginFailed);
        }
        try{
            $CouponCount =CouponCustomer::CouponCount($checkResult['customer_id']);
        }catch (\Exception $e) {
            return $this->send($e, "获取用户优惠券数量系统错误", 1024, 200,null,alertMsgEnum::bossError);
        }
        $ret['couponCount'] = $CouponCount;
        return $this->send($ret, "获取用户优惠券数量成功", 1, 200,null,alertMsgEnum::getCouponCountSuccess);
    }
}
?>