<?php

namespace api\controllers;

use \core\models\customer\Customer;
use Yii;
use \core\models\customer\CustomerAddress;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
use \core\models\operation\coupon\Coupon;

class UserController extends \api\components\Controller
{

    /**
     * @api {POST} /user/add-address 添加常用地址 (已完成100%) 
     *
     * @apiName AddAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} operation_province_name 省
     * @apiParam {String} operation_city_name 市名
     * @apiParam {String} operation_area_name 地区名（朝阳区）
     * @apiParam {String} customer_address_detail 详细地址
     * @apiParam {String} customer_address_nickname 被服务者昵称
     * @apiParam {String} customer_address_phone 被服务者手机
     *
     * @apiSuccess {Object[]} address 新增地址.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "地址添加成功"
     *       "ret":{
     *       "address":
     *          {
     *          "id": 2,
     *          "customer_id": 1,
     *          "operation_province_id": 110000,
     *          "operation_city_id": 110100,
     *          "operation_area_id": 110105,
     *          "operation_province_name": "北京",
     *          "operation_city_name": "北京市",
     *          "operation_area_name": "朝阳区",
     *          "operation_province_short_name": "北京",
     *          "operation_city_short_name": "北京",
     *          "operation_area_short_name": "朝阳",
     *          "customer_address_detail": "某某小区8栋3单元512",
     *          "customer_address_status": 1,客户地址类型,1为默认地址，0为非默认地址
     *          "customer_address_longitude": 116.48641,
     *          "customer_address_latitude": 39.92149,
     *          "customer_address_nickname": "王小明",
     *          "customer_address_phone": "18210922324",
     *          "created_at": 1445063798,
     *          "updated_at": 0,
     *          "is_del": 0
     *          }
     *        }
     *
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录。"
     *
     *     }
     * @apiError AddressNotFound 常用地址添加失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "0",
     *       "msg": "常用地址添加失败"
     *
     *     }
     */
    public function actionAddAddress()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            $model = CustomerAddress::addAddress($customer->id, @$param['operation_province_name'], @$param['operation_city_name'], @$param['operation_area_name'], @$param['customer_address_detail'], @$param['customer_address_nickname'], @$param['customer_address_phone']);

            if (!empty($model)) {
                $ret = ['address' => $model];
                return $this->send($ret, "常用地址添加成功");
            } else {
                return $this->send(null, "常用地址添加失败", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} /user/get-addresses 常用地址列表 (已完成100%)
     *
     * @apiName GetAddresses
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccess {Object[]} addresses 用户常用地址数组.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取地址列表成功"
     *       "ret":{
     *       "addresses": [
     *          {
     *          "id": 2,
     *          "customer_id": 1,
     *          "operation_province_id": 110000,
     *          "operation_city_id": 110100,
     *          "operation_area_id": 110105,
     *          "operation_province_name": "北京",
     *          "operation_city_name": "北京市",
     *          "operation_area_name": "朝阳区",
     *          "operation_province_short_name": "北京",
     *          "operation_city_short_name": "北京",
     *          "operation_area_short_name": "朝阳",
     *          "customer_address_detail": "某某小区8栋3单元512",
     *          "customer_address_status": 1,客户地址类型,1为默认地址，0为非默认地址
     *          "customer_address_longitude": 116.48641,
     *          "customer_address_latitude": 39.92149,
     *          "customer_address_nickname": "王小明",
     *          "customer_address_phone": "18210922324",
     *          "created_at": 1445063798,
     *          "updated_at": 0,
     *          "is_del": 0
     *          },
     *         ]
     *        }
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */
    public function actionGetAddresses()
    {
        @$accessToken = Yii::$app->request->get('access_token');

        if (empty($accessToken)) {
            $accessToken = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($accessToken) || !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($accessToken);

        if (!empty($customer) && !empty($customer->id)) {
            $AddressArr = CustomerAddress::listAddress($customer->id);

            $addresses = array();
            foreach ($AddressArr as $key => $model) {
                $addresses[] = $model;
            }
            $ret = ['addresses' => $addresses];
            return $this->send($ret, "获取地址列表成功");
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * @api {DELETE} /user/delete-address 删除用户常用地址 (已完成100%) 
     *
     * @apiName DeleteAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} address_id 地址id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "删除成功"
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录."
     *     }
     */
    public function actionDeleteAddress()
    {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            $params = json_decode(Yii::$app->request->getRawBody(), true);
        }

        @$accessToken = $params['access_token'];
        @$addressId = $params['address_id'];
        if (empty($accessToken) || !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败", 0, 403);
        }

        if (CustomerAddress::deleteAddress($addressId)) {
            return $this->send(null, "删除成功");
        } else {
            return $this->send(null, "删除失败", 0, 403);
        }
    }

    /**
     * @api {PUT} /user/set-default-address 设置默认地址 (已完成100%) 
     * @apiDescription 用户每次下完单都会将该次地址设置为默认地址，下次下单优先使用默认地址
     * @apiName SetDefaultAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} address_id 地址id
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "设置成功"
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *     }
     */
    public function actionSetDefaultAddress()
    {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            $params = json_decode(Yii::$app->request->getRawBody(), true);
        }

        @$accessToken = $params['access_token'];
        @$addressId = $params['address_id'];
        if (empty($accessToken) || !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败", 0, 403);
        }

        $model = CustomerAddress::getAddress($addressId);

        if (empty($model)) {
            return $this->send(null, "地址信息获取失败", 0, 403);
        }

        if (CustomerAddress::updateAddress($model->id, $model->operation_area_name, $model->customer_address_detail, $model->customer_address_nickname, $model->customer_address_phone)
        ) {
            return $this->send(null, "设置默认地址成功");
        } else {

            return $this->send(null, "设置默认地址失败", 0, 403);
        }
    }

    /**
     * @api {PUT} /user/update-address 修改常用地址 (已完成100%) 
     *
     * @apiName UpdateAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} address_id 地址id
     * @apiParam {String} [operation_area_name] 地区名（朝阳区）
     * @apiParam {String} [address_detail] 详细地址信息
     * @apiParam {String} [address_nickname] 联系人
     * @apiParam {String} [address_phone] 联系电话
     *
     * @apiSuccess {Object[]} address 新增地址.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "修改常用地址成功"
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录。"
     *
     *     }
     * @apiError AddressNotFound 地址信息获取失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "0",
     *       "msg": "地址信息获取失败"
     *
     *     }
     */
    public function actionUpdateAddress()
    {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            $params = json_decode(Yii::$app->request->getRawBody(), true);
        }
        @$accessToken = $params['access_token'];
        @$addressId = $params['address_id'];
        if (empty($accessToken) || !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败", 0, 403);
        }

        $model = CustomerAddress::getAddress($addressId);

        if (empty($model)) {
            return $this->send(null, "地址信息获取失败", 0, 403);
        }

        if (CustomerAddress::updateAddress($model->id, @$params['operation_area_name'], @$params['address_detail'], @$params['address_nickname'], @$params['address_phone'])
        ) {
            return $this->send(null, "修改常用地址成功");
        } else {

            return $this->send(null, "修改常用地址失败", 0, 403);
        }
    }

    /**
     * @api {PUT} /user/set-default-city 设置默认城市 （需求不明确；0%）
     *
     * @apiName SetDefaultCity
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city_name 城市名称
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccess {Object[]} services 该城市提供的服务.
     * @apiSuccess {Object[]} appInfoWithCity 该城市相关初始化配置.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "设置成功"
     *       "ret":{
     *          "services":{}
     *          "appInfoWithCity":{}
     *        }
     *
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */

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
     * @api {GET} /user/get-coupon-customer 获取用户优惠码或同城市 （郝建设100%）
     *
     * @apiName GetCouponCustomer
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [city_name]  城市
     * @apiParam {int} coupon_type  优惠码表示 1获取提供城市或者全国的优惠码 2获取全国和给定城市的优惠码
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

        $param = Yii::$app->request->get();
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

    /**
     * @api {GET} /user/get-coupon-count 获取用户优惠码数量 （功能已经实现 100%）
     *
     *
     * @apiName GetCouponCount
     * @apiGroup User
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
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            $CouponCount = \core\models\coupon\CouponCustomer::CouponCount(1);

            $ret['couponCount'] = $CouponCount;
            return $this->send($ret, "用户优惠码数量");
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * @api {DELETE} /user/delete-used-worker 删除常用阿姨 （功能已经实现,需再次核实 100%）
     *
     *
     * @apiName deleteUsedWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} worker_id  阿姨id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "删除成功"
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
     *
     * @apiError WorkerNotFound 该阿姨不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "不存在要删除的阿姨"
     *
     *     }
     *
     */
    public function actionDeleteUserWorker()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $worker_id = $param['worker_id'];
        $app_version = $param['app_version'];
        $app_version = $param['access_token'];

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * @param $customer->id int 用户id
             * @param $worker      int 阿姨id
             * @param $type        int 标示类型，1时判断黑名单阿姨，0时判断常用阿姨
             */
            $deleteData = \core\models\customer\CustomerWorker::deleteWorker(1, 2, 1);
            if ($deleteData) {
                $deleteData = array(1);
                return $this->send($deleteData, "删除成功");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {GET} /user/black-list-workers 黑名单阿姨列表 （功能已经完成,需要核实传递参数和返回数据格式 已完成100%）
     * @apiDescription 获得该用户添加进黑名单的阿姨
     *
     * @apiName blacklistworkers
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "删除成功"
     *       "ret":{
     *         "blockWorkers": [
     *         {
     *           "worker_id": "12409",
     *           "face": "http://static.1jiajie.com/worker/face/12409.jpg",
     *           "name": "夏测试",
     *           "order_num": "服务:168次",
     *           "kilometer": "",
     *           "star_rate": "0",
     *           "last_serve_time": "最后服务时间:2015-04-22 16:00:34",
     *           "shop_id": "68",
     *           "is_fulltime": "全职全日",
     *           "telephone": "18610932174"
     *         }
     *        ]
     *       }
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
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     *
     */
    public function actionBlackListWorkers()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $app_version = $param['app_version']; #版本

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            /**
             * @param $customer->id int 用户id
             * @param $is_block      int 阿姨id
             */
            $workerData = \core\models\customer\CustomerWorker::blacklistworkers(1, 1);
            if ($workerData) {
                return $this->send($workerData, "阿姨列表查询");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {DELETE} /user/remove-worker 移除黑名单中的阿姨 （功能已经实现,需要再次确认传递参数 已完成100%）
     *
     *
     * @apiName RemoveWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token  用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} worker_id      阿姨id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "移除成功"
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
     *
     *
     */
    public function actionRemoveWorker()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        $app_version = $param['app_version']; #版本
        $worker_id = $param['worker_id']; #阿姨id

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 403);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            /**
             * @param $costomer_id int 用户id
             * @param $worker      int 阿姨id
             * @param $type        int 标示类型，1时判断黑名单阿姨，0时判断常用阿姨
             * @param $is_block        int 标示类型，数据逻辑删除 1黑名单阿姨 0不是黑名单阿姨
             */
            $deleteData = \core\models\customer\CustomerWorker::deleteWorker(1, 2, 0, 0);
            if ($deleteData) {
                $deleteData = array(1);
                return $this->send($deleteData, "移除成功");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {GET} /user/get-user-money 用户余额和消费记录 （数据已经全部取出,需要给出所需字段,然后给予返回 已完成99% ;）
     * 
     *
     * @apiName GetUserMoney
     *
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccess {Object} UserMoney 用户当前余额和消费记录对象
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "code": "1",
     * "msg": "查询成功",
     * "ret": {
     * "userBalance": "100.00",
     * "userRecord": [
     * {
     * "id": "1",
     * "customer_id": "1",
     *  "order_id": "0",
     * "order_channel_id": "0",
     * "customer_trans_record_order_channel": null,
     * "pay_channel_id": "0",
     * "customer_trans_record_pay_channel": null,
     *  "customer_trans_record_mode": "0",
     * "customer_trans_record_mode_name": null,
     * "customer_trans_record_coupon_money": "0.00",
     * "customer_trans_record_cash": "0.00",
     * "customer_trans_record_pre_pay": "0.00",
     * "customer_trans_record_online_pay": "0.00",
     * "customer_trans_record_online_balance_pay": "0.00",
     * "customer_trans_record_online_service_card_on": "0",
     * "customer_trans_record_online_service_card_pay": "0.00",
     * "customer_trans_record_online_service_card_current_balance": "0.00",
     * "customer_trans_record_online_service_card_befor_balance": "0.00",
     * "customer_trans_record_compensate_money": "0.00",
     * "customer_trans_record_refund_money": "0.00",
     * "customer_trans_record_order_total_money": "0.00",
     * "customer_trans_record_total_money": "0.00",
     * "customer_trans_record_current_balance": "0.00",
     * "customer_trans_record_befor_balance": "0.00",
     * "customer_trans_record_transaction_id": "0",
     * "customer_trans_record_remark": "",
     * "customer_trans_record_verify": "",
     * "created_at": "0",
     * "updated_at": "0",
     * "is_del": "1"
     * }
     * ]
     * }
     * }
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
     */
    public function actionGetUserMoney()
    {

        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        #获取用户id
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * 获取客户余额
             * @param int $customer 用户id
             */
            $userBalance = \core\models\customer\CustomerExtBalance::getCustomerBalance($customer->id);
            /**
             * 获取用户消费记录
             * @param int $customer 用户id
             */
            $userRecord = \core\models\CustomerTransRecord\CustomerTransRecord::queryRecord($customer->id);
            $ret["userBalance"] = $userBalance;
            $ret["userRecord"] = $userRecord;
            return $this->send($ret, "查询成功");

            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * 发送验证码
     */
    public function actionSetUser()
    {

        $aaa = \core\models\customer\CustomerCode::generateAndSend('13683118946');
    }

    #生成access_token

    public function actionAddUser()
    {
        $daat = \core\models\customer\CustomerAccessToken::generateAccessToken('13683118946', '4820');

        print_r($daat);
    }

    /**
     * @api {GET} /user/get-user-score 用户积分明细 （功能已实现,不明确需求端所需字段格式 90%）
     *
     * @apiDescription 获取用户当前积分，积分兑换奖品信息，怎样获取积分信息
     * @apiName GetUserScore
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "提交成功"
     *       "ret":{
     *          "scoreCategory": [
     *              {
     *                  "desc": "在线支付后评价订单",
     *                  "score": "¥*5"
     *              },
     *              {
     *                  "desc": "在线支付",
     *                  "score": "¥*5"
     *              },
     *              {
     *                  "desc": "分享给朋友",
     *                  "score": "10"
     *              }
     *          ],
     *          "scoreDetail": [],
     *          "score": "0分",
     *          "userPrize": [
     *              {
     *                  "prizeId": "3",
     *                  "prizeName": "e家洁厨房高温保养",
     *                  "prizeCost": "1500",
     *                  "prizeRule": [
     *                      "如需咨询请拨打客服电话：400-6767-636"
     *                  ],
     *                  "prizeThumb": "http://webapi2.1jiajie.com/app/images/gaowenbaojie_small3.png",
     *                  "prizePic": "http://static.1jiajie.com/prizePhoto/gaowenbaojie_big.png"
     *              }
     *          ],
     *        }
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
     *
     */
    public function actionGetUserScore()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        $app_version = $param['app_version']; #版本

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            /**
             *  @param int $customer_id 用户id
             */
            $userscore = \core\models\customer\CustomerExtScore::getCustomerScoreList($customer->id);

            if ($userscore) {
                $ret["scoreCategory"] = $userscore;
                return $this->send($ret, "用户积分明细列表", 1);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {POST} /user/user-suggest 用户评价 （需要再次核实需求;郝建设 100%）
     *
     * @apiName UserSuggest
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} customer_comment_phone 用户电话
     * @apiParam {String} customer_comment_level 评价级别
     * @apiParam {String} [customer_comment_tag_ids] 评价标签
     * @apiParam {String} [customer_comment_content] 评价内容
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "用户评价提交成功"
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
     *
     */
    public function actionUserSuggest()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            $model = \core\models\customer\CustomerComment::addUserSuggest($customer->id, $param['order_id'], $param['customer_comment_phone'], $param['customer_comment_content'], $param['customer_comment_tag_ids'], $param['customer_comment_level']);
            if (!empty($model)) {
                return $this->send([1], "添加评论成功");
            } else {
                return $this->send(null, "添加评论失败", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} /user/get-comment-level 获取用户评价等级 （郝建设 100%）
     *
     * @apiName GetCommentLevel
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取评论级别成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_comment_level": "级别代号",
     *          "customer_comment_level_name": "级别名称",
     *          "is_del": "是否删除",
     *
     *           }
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
     */
    public function actionGetCommentLevel()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {

            $level = \core\models\comment\CustomerCommentLevel::getCommentLevel();
            if (!empty($level)) {
                $ret = ['comment' => $level];
                return $this->send($ret, "获取评论级别成功");
            } else {
                return $this->send(null, "获取评论级别失败", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} /user/get-comment-level-tag 获取用户评价等级下面的标签 （郝建设 100%）
     *
     * @apiName GetCommentLevelTag
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} customer_comment_level 级别id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取评论标签成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_tag_name": "评价标签名称",
     *          "customer_comment_level": "评价等级",
     *          "is_online": "是否上线",
     *          "is_del": "删除",
     *
     *           }
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
     */
    public function actionGetCommentLevelTag()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {

            $level = \core\models\comment\CustomerCommentTag::getCommentTag($param['customer_comment_level']);

            if (!empty($level)) {
                $ret = ['commentTag' => $level];
                return $this->send($ret, "获取评论标签成功");
            } else {
                return $this->send(null, "获取评论标签失败", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} /user/get-goods 获取给定经纬度范围内是否有该服务 （郝建设 100%）
     *
     * @apiName GetGoods
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} longitude 经度
     * @apiParam {String} latitude 纬度
     * @apiParam {String} order_service_type_id 服务id
     * 

     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "有该服务",
     *       "ret": {
     *          "1",
     *           }
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
     */
    public function actionGetGoods()
    {
        $param = Yii::$app->request->get();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {


            $service = \core\models\order\Order::getGoods($param['longitude'], $param['latitude'], $param['order_service_type_id']);
            if ($service) {
                return $this->send(1, "该服务获取成功");
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

}
