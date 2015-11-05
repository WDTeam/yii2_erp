<?php

namespace restapi\controllers;

use restapi\models\alertMsgEnum;
use Yii;
use \core\models\customer\Customer;
use \core\models\customer\CustomerAddress;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
use \core\models\operation\coupon\Coupon;
use \core\models\payment\PaymentCustomerTransRecord;
use \core\models\order\Order;
use \core\models\customer\CustomerComment;
use \core\models\comment\CustomerCommentTag;
use \core\models\comment\CustomerCommentLevel;
use \core\models\customer\CustomerExtScore;

class UserController extends \restapi\components\Controller
{

    /**
     * @api {POST} /user/add-address  [POST]/user/add-address(100%)
     * @apiDescription 添加常用地址
     * @apiName actionAddAddress
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
     *       "msg": "地址添加成功",
     *       "alertMsg":"常用地址添加成功",
     *       "ret":{
     *       "address":
     *          {
     *          "id": 主键,
     *          "customer_id":关联客户,
     *          "operation_province_id": 省id,
     *          "operation_city_id": 市id,
     *          "operation_area_id": 区id,
     *          "operation_province_name": "省名字",
     *          "operation_city_name": "市名字",
     *          "operation_area_name": "区名字",
     *          "operation_province_short_name": "省短名",
     *          "operation_city_short_name": "市短名",
     *          "operation_area_short_name": "区短名",
     *          "customer_address_detail": "详细地址",
     *          "customer_address_status": 客户地址类型,1为默认地址，-1为非默认地址,
     *          "customer_address_longitude": 经度,
     *          "customer_address_latitude": 纬度,
     *          "customer_address_nickname": "用户昵称",
     *          "customer_address_phone": "被服务者手机",
     *          "created_at": 创建时间,
     *          "updated_at": 更新时间,
     *          }
     *        }
     *     }
     *
     * @apiError AddressNotFound 常用地址添加失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "0",
     *       "msg": "常用地址添加失败",
     *       "ret": {},
     *       "alertMsg":"常用地址添加失败",
     *     }
     */
    public function actionAddAddress()
    {
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }

        //控制添加地址时手机号码必须填写
        if (empty($param['customer_address_phone']) || empty($param['customer_address_nickname'])) {
            return $this->send(null, "被服务者手机或被服务者昵称不能为空", 0, 200, null, alertMsgEnum::addAddressNoPhone);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            $model = CustomerAddress::addAddress($customer->id, @$param['operation_province_name'], @$param['operation_city_name'], @$param['operation_area_name'], @$param['customer_address_detail'], @$param['customer_address_nickname'], @$param['customer_address_phone']);

            #销毁删除表示
            unset($model['is_del']);

            if (!empty($model)) {
                $ret = ['address' => $model];
                return $this->send($ret, "常用地址添加成功", 1, 200, null, alertMsgEnum::addAddressSuccess);
            } else {
                return $this->send(null, "常用地址添加失败", 0, 200, null, alertMsgEnum::addAddressFail);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-addresses [GET] /user/get-addresses (100%)
     * @apiDescription 获取常用地址列表
     * @apiName actionGetAddresses
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * 
     * @apiSuccess {Object[]} addresses 用户常用地址数组.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取地址列表成功"
     *       "ret":{
     *       "addresses": [
     *         {
     *          "id": 主键,
     *          "customer_id":关联客户,
     *          "operation_province_id": 省id,
     *          "operation_city_id": 市id,
     *          "operation_area_id": 区id,
     *          "operation_province_name": "省名字",
     *          "operation_city_name": "市名字",
     *          "operation_area_name": "区名字",
     *          "operation_province_short_name": "省短名",
     *          "operation_city_short_name": "市短名",
     *          "operation_area_short_name": "区短名",
     *          "customer_address_detail": "详细地址",
     *          "customer_address_status": 客户地址类型,1为默认地址，-1为非默认地址,
     *          "customer_address_longitude": 经度,
     *          "customer_address_latitude": 纬度,
     *          "customer_address_nickname": "用户昵称",
     *          "customer_address_phone": "被服务者手机",
     *          "created_at": 创建时间,
     *          "updated_at": 更新时间,
     *          },
     *         ]
     *        }
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录",
     *       "ret": {},
     *       "alertMsg":"获取常用地址列表失败",
     *     }
     */
    public function actionGetAddresses()
    {
        @$accessToken = Yii::$app->request->get('access_token');

        if (empty($accessToken)) {
            $accessToken = json_decode(Yii::$app->request->getRawBody(), true);
        }

        if (empty($accessToken) || !CustomerAccessToken::checkAccessToken($accessToken)) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }

        $customer = CustomerAccessToken::getCustomer($accessToken);

        try {
            if (!empty($customer) && !empty($customer->id)) {
                $AddressArr = CustomerAddress::listAddress($customer->id);

                $addresses = array();
                foreach ($AddressArr as $key => $model) {
                    $addresses[] = $model;
                    unset($model->is_del);
                }
                $ret = ['addresses' => $addresses];
                return $this->send($ret, "获取地址列表成功", 1, 200, null, alertMsgEnum::getAddressesSuccess);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
            }
        } catch (\Exception $e) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {DELETE} /user/delete-address [DELETE] /user/delete-address (100%)
     * @apiDescription 删除用户常用地址
     * @apiName actionDeleteAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {int}    address_id 地址id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "alertMeg": "删除成功",
     *       "ret": {},
     *       "msg": "删除成功"
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录."，
     *        "ret": {},
     *       "alertMeg": "删除失败"
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
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败,请添加地址id", 0, 200, null, alertMsgEnum::deleteAddressNoAddressId);
        }

        if (CustomerAddress::deleteAddress($addressId)) {
            return $this->send(null, "删除成功", 1, 200, null, alertMsgEnum::deleteAddressSuccess);
        } else {
            return $this->send(null, "删除失败", 0, 200, null, alertMsgEnum::deleteAddressFail);
        }
    }

    /**
     * @api {PUT} /user/set-default-address [PUT] /user/set-default-address(100%)
     * @apiDescription 设置默认地址【用户每次下完单都会将该次地址设置为默认地址，下次下单优先使用默认地址】
     * @apiName actionSetDefaultAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {int}    address_id 地址id
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} [operation_province_name] 省
     * @apiParam {String} [operation_city_name] 市名
     * @apiParam {String} [operation_area_name] 地区名（朝阳区）
     * @apiParam {String} [customer_address_detail] 详细地址
     * @apiParam {String} [customer_address_nickname] 被服务者昵称
     * @apiParam {String} [customer_address_phone] 被服务者手机
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "设置默认地址成功",
     *       "ret": {},
     *      "alertMeg": "设置默认地址成功"
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "地址信息获取失败",
     *       "ret": {},
     *       "alertMeg": "地址信息获取失败"
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
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败", 0, 200, null, alertMsgEnum::setDefaultAddressNoAddressId);
        }

        $model = CustomerAddress::getAddress($addressId);

        if (empty($model)) {
            return $this->send(null, "地址信息获取失败", 0, 200, null, alertMsgEnum::setDefaultAddressNoAddressId);
        }
        try {
            if (CustomerAddress::updateAddress($model->id, $model->operation_province_name, $model->operation_city_name, $model->operation_area_name, $model->customer_address_detail, $model->customer_address_nickname, $model->customer_address_phone)
            ) {
                return $this->send(null, "设置默认地址成功", 1, 200, null, alertMsgEnum::setDefaultAddressSuccess);
            } else {

                return $this->send(null, "设置默认地址失败", 0, 200, null, alertMsgEnum::setDefaultAddressFail);
            }
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
        }
    }

    /**
     * @api {PUT} /user/update-address [PUT] /user/update-address(100%)
     * @apiDescription 修改常用地址
     * @apiName actionUpdateAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {int}    address_id 地址id
     * @apiParam {String} [operation_province_name] 省
     * @apiParam {String} [operation_city_name] 市名
     * @apiParam {String} [operation_area_name] 地区名（朝阳区）
     * @apiParam {String} [customer_address_detail] 详细地址信息
     * @apiParam {String} [customer_address_nickname] 被服务者昵称
     * @apiParam {String} [customer_address_phone] 被服务者手机
     *
     * @apiSuccess {Object[]} address 新增地址.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "修改常用地址成功",
     *       "ret": {},
     *       "alertMsg": "修改常用地址成功"
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "ret": {},
     *       "msg": "用户认证已经过期,请重新登录。",
     *       "alertMeg": "用户认证已经过期,请重新登录。",
     *     }
     * @apiError AddressNotFound 地址信息获取失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "0",
     *       "ret": {},
     *       "alertMeg": "用户认证已经过期,请重新登录。",
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
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
        if (empty($addressId)) {
            return $this->send(null, "地址信息获取失败", 0, 200, null, alertMsgEnum::updateAddressNoAddressId);
        }
        $model = CustomerAddress::getAddress($addressId);

        if (empty($model)) {
            return $this->send(null, "地址信息获取失败", 0, 200, null, alertMsgEnum::updateAddressNoAddressId);
        }

        try {
            if (CustomerAddress::updateAddress($model->id, @$params['operation_province_name'], @$params['operation_city_name'], @$params['operation_area_name'], @$params['customer_address_detail'], @$params['customer_address_nickname'], @$params['customer_address_phone'])
            ) {
                return $this->send(null, "修改常用地址成功", 1, 200, null, alertMsgEnum::updateAddressSuccess);
            } else {

                return $this->send(null, "修改常用地址失败", 0, 200, null, alertMsgEnum::updateAddressFail);
            }
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
        }
    }

    /**
     * @api {GET} /user/default-address  [GET] /user/default-address(100%)
     * @apiDescription 获取默认地址(赵顺利)
     * @apiName actionDefaultAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "修改常用地址成功"
     *       "ret":{
     *       "address":
     *         {
     *          "id": 主键,
     *          "customer_id":关联客户,
     *          "operation_province_id": 省id,
     *          "operation_city_id": 市id,
     *          "operation_area_id": 区id,
     *          "operation_province_name": "省名字",
     *          "operation_city_name": "市名字",
     *          "operation_area_name": "区名字",
     *          "operation_province_short_name": "省短名",
     *          "operation_city_short_name": "市短名",
     *          "operation_area_short_name": "区短名",
     *          "customer_address_detail": "详细地址",
     *          "customer_address_status": 客户地址类型,1为默认地址，-1为非默认地址,
     *          "customer_address_longitude": 经度,
     *          "customer_address_latitude": 纬度,
     *          "customer_address_nickname": "用户昵称",
     *          "customer_address_phone": "被服务者手机",
     *          "created_at": 创建时间,
     *          "updated_at": 更新时间,
     *          }
     *        }
     *
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
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
    public function actionDefaultAddress()
    {
        $params = Yii::$app->request->get() or $params = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty($params['access_token']) || !CustomerAccessToken::checkAccessToken($params['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
        $customer = CustomerAccessToken::getCustomer($params['access_token']);

        if (!empty($customer) && !empty($customer->id)) {

            try {
                $Address = CustomerAddress::getCurrentAddress($customer->id);
                if (empty($Address)) {
                    return $this->send(null, "该用户没有默认地址", 0, 200, null, alertMsgEnum::defaultAddressNoAddress);
                }
                $ret = ['address' => $Address];
                return $this->send($ret, "获取默认地址成功", 1, 200, null, alertMsgEnum::defaultAddressSuccess);
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "获取用户信息失败", "0", 200, null, alertMsgEnum::defaultAddressFail);
        }
    }

    /**
     * @api {DELETE} /user/delete-used-worker [DELETE] /user/delete-used-worker （100%）
     * @apiDescription 删除常用阿姨 [该功能已经砍掉]
     *
     * @apiName actionDeleteUsedWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {int}    worker_id  阿姨id
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
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     * @apiError WorkerNotFound 该阿姨不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
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
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            /**
             * @param $customer ->id int 用户id
             * @param $worker      int 阿姨id
             * @param $type        int 标示类型，1时判断黑名单阿姨，0时判断常用阿姨
             */
            $deleteData = \core\models\customer\CustomerWorker::deleteWorker(1, 2, 1);
            if ($deleteData) {
                $deleteData = array(1);
                return $this->send($deleteData, "删除成功", 1, 200, null, alertMsgEnum::deleteUserWorkerSuccess);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
            }
        }
    }

    /**
     * @api {GET} /user/black-list-workers [GET] /user/black-list-workers（100%）
     * @apiDescription 获得该用户添加进黑名单的阿姨 [该功能已经砍掉]
     *
     * @apiName actionBlackListWorkers
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
     *     HTTP/1.1 200 Not Found
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
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            /**
             * @param $customer ->id int 用户id
             * @param $is_block      int 阿姨id
             */
            $workerData = \core\models\customer\CustomerWorker::blacklistworkers(1, 1);
            if ($workerData) {
                return $this->send($workerData, "阿姨列表查询", 1, null, alertMsgEnum::blackListWorkersSuccess);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
            }
        }
    }

    /**
     * @api {DELETE} /user/remove-worker [DELETE] /user/remove-worker （100%）
     *
     * @apiDescription 移除黑名单中的阿姨 [该功能已经砍掉]
     * @apiName actionRemoveWorker 
     * @apiGroup User
     *
     * @apiParam {String} access_token  用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {int}    worker_id      阿姨id
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
     *     HTTP/1.1 200 Not Found
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
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 200, null, alertMsgEnum::userLoginFailed);
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
                return $this->send($deleteData, "移除成功", 1, 200, null, alertMsgEnum::removeWorkerSuccess);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
            }
        }
    }

    /**
     * @api {GET} /user/get-user-money  [GET] /user/get-user-money（100%）
     *
     * @apiDescription 用户余额和消费记录 (郝建设)
     * @apiName actionGetUserMoney
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
     * "alertMsg": "查询成功",
     * "ret": {
     * "userBalance": "用户余额",
     * "userRecord": [
     * {
     * "id": "1",
     * "customer_id": "用户ID",
     *  "order_id": "订单ID",
     * "order_channel_id": "订单渠道",
     * "customer_trans_record_order_channel": 订单渠道名称,
     * "pay_channel_id": "支付渠道",
     * "customer_trans_record_pay_channel": 支付渠道名称,
     *  "customer_trans_record_mode": "交易方式:1消费,2=充值,3=退款,4=赔偿",
     * "customer_trans_record_mode_name": 交易方式名称,
     * "customer_trans_record_coupon_money": "优惠券金额",
     * "customer_trans_record_cash": "现金支付",
     * "customer_trans_record_pre_pay": "预付费金额（第三方）",
     * "customer_trans_record_online_pay": "在线支付",
     * "customer_trans_record_online_balance_pay": "在线余额支付",
     * "customer_trans_record_online_service_card_on": "服务卡号",
     * "customer_trans_record_online_service_card_pay": "服务卡支付",
     * "customer_trans_record_online_service_card_current_balance": "服务卡当前余额",
     * "customer_trans_record_online_service_card_befor_balance": "服务卡之前余额",
     * "customer_trans_record_compensate_money": "补偿金额",
     * "customer_trans_record_refund_money": "退款金额",
     * "customer_trans_record_order_total_money": "订单总额",
     * "customer_trans_record_total_money":'交易总额',
     * "customer_trans_record_current_balance":'当前余额',
     * "customer_trans_record_befor_balance":'之前余额',
     * "customer_trans_record_transaction_id":'交易流水号',
     * "customer_trans_record_remark": '备注',
     * "customer_trans_record_verify": '验证',
     * "created_at":'创建时间',
     * "updated_at":'更新时间',
     * }
     * ]
     * }
     * }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}

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
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }


        #获取用户id
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            try {
                /**
                 * 获取客户余额
                 * @param int $customer 用户id
                 */
                $userBalance = Customer::getBalanceById($customer->id);

                if ($userBalance['response'] == 'success') {
                    $userBalanceMoney = $userBalance['balance'];
                } elseif ($userBalance['response'] == 'error') {
                    return $this->send(null, $userBalance['errmsg'], 0, 200);
                }

                /**
                 * 获取用户消费记录
                 *
                 * @param int $customer 用户id
                 */
                $userRecord = PaymentCustomerTransRecord::getCustomerPaymentTransRecord($customer->id);
                $ret["userBalance"] = $userBalanceMoney;
                $ret["userRecord"] = $userRecord;

                return $this->send($ret, "查询成功", 1, 200, null, alertMsgEnum::getUserMoneySuccess);
            } catch (\Exception $e) {
                return $this->send($e, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }

            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-user-score [GET] /user/get-user-score（100%）
     *
     * @apiDescription 获取用户当前积分，积分兑换奖品信息，怎样获取积分信息
     * @apiName actionGetUserScore
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "用户积分明细列表",
     * "alertMsg": "用户积分明细列表",
     * "ret": {
     * "scoreCategory": [
     *      {
     *       "id": "1",
     *       "customer_id": "客户",
     *       "customer_score": "客户积分",
     *       "created_at": "创建时间",
     *       "updated_at": "更新时间",
     *       }
     *      ]
     *     }
     *    }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录",
     *       "alertMsg": "用户认证已经过期,请重新登录",
     *       "ret": {},
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

        @$app_version = $param['app_version']; #版本

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 200, null, alertMsgEnum::userLoginFailed);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            try {
                /**
                 * @param int $customer_id 用户id
                 */
                $userscore = CustomerExtScore::getCustomerScoreList($customer->id);

                $array = array();
                foreach ($userscore as $key => $val) {
                    unset($val['is_del']);
                    $array[$key] = $val;
                }

                if ($array) {
                    $ret["scoreCategory"] = $array;
                    return $this->send($ret, "用户积分明细列表", 1, 200, null, alertMsgEnum::getUserScoreSuccess);
                } else {
                    return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        }
    }

    /**
     * @api {POST} /user/user-suggest [POST] /user/user-suggest （100%）
     * @apiDescription 用户评价 (郝建设)
     * @apiName actionUserSuggest
     * @apiGroup User
     * @apiParam {int} order_id       '订单ID'
     * @apiParam {String} access_token 用户认证
     * @apiParam {int}  worker_id      '阿姨id'
     * @apiParam {String} worker_tel  '阿姨电话'
     * @apiParam {int}    operation_shop_district_id '商圈id'
     * @apiParam {int}   province_id    '省id'
     * @apiParam {int}   city_id        '市id'
     * @apiParam {int}   county_id      '区id'
     * @apiParam {String} customer_comment_phone    '用户电话'
     * @apiParam {String} customer_comment_content  '评论内容'
     * @apiParam {int}    customer_comment_level       '评论等级'
     * @apiParam {String} customer_comment_level_name '评价等级名称'
     * @apiParam {String} customer_comment_tag_ids  '评价标签'
     * @apiParam {String} customer_comment_tag_names '评价标签名称'
     * @apiParam {int}    customer_comment_anonymous  是否匿名评价,0匿名,1非匿名'
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "用户评价提交成功", 
     *       "alertMsg": "用户评价提交成功",
     *       "ret": {}
     *
     *     }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}
     *     }
     *
     */
    public function actionUserSuggest()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (empty($param['order_id']) || empty($param['customer_comment_phone'])) {
            return $this->send(null, "提交参数中缺少必要的参数.", 0, 200, null, alertMsgEnum::userSuggestNoOrder);
        }

        #是否匿名评价,0匿名,1非匿名'
        if (empty($param['customer_comment_anonymous'])) {
            $param['customer_comment_anonymous'] = 0;
        }

        #商圈id
        if (empty($param['operation_shop_district_id'])) {
            $param['operation_shop_district_id'] = 0;
        }
        #评价内容
        if (empty($param['customer_comment_content'])) {
            $param['customer_comment_content'] = 0;
        }
        #评论等级
        if (empty($param['customer_comment_level'])) {
            $param['customer_comment_level'] = 0;
        }
        #评价等级名称
        if (empty($param['customer_comment_level_name'])) {
            $param['customer_comment_level_name'] = 0;
        }
        #评价标签
        if (empty($param['customer_comment_tag_ids'])) {
            $param['customer_comment_tag_ids'] = 0;
        }
        try {
            if (!empty($customer) && !empty($customer->id)) {

                $param['customer_id'] = $customer->id;
                $model = CustomerComment::addUserSuggest($param);
               
                if (!empty($model)) {
                    return $this->send(null, "添加评论成功", 1, 200, null, alertMsgEnum::userSuggestSuccess);
                } else {
                    return $this->send(null, "添加评论失败", 0, 200, null, alertMsgEnum::userSuggestFail);
                }
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
            }
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
        }
    }

    /**
     * @api {GET} /user/get-comment-level [GET] /user/get-comment-level （100%）
     * @apiDescription 获取用户评价等级(郝建设)
     * @apiName actionGetCommentLevel
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
     *       "alertMsg": "获取评论级别成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_comment_level": "级别代号",
     *          "customer_comment_level_name": "级别名称"
     *           }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}
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
            try {
                $level = CustomerCommentLevel::getCommentLevel();

                $array = [];
                foreach ($level as $key => $val) {
                    unset($val['is_del']);
                    $array[$key] = $val;
                }

                if (!empty($array)) {
                    $ret = ['comment' => $array];
                    return $this->send($ret, "获取评论级别成功", 1, 200, null, alertMsgEnum::getCommentLevelSuccess);
                } else {
                    return $this->send(null, "获取评论级别失败", 0, 200, null, alertMsgEnum::getCommentLevelFail);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-comment-level-tag  [GET] /user/get-comment-level-tag（100%）
     * @apiDescription 获取用户评价等级下面的标签(郝建设)
     * @apiName actionGetCommentLevelTag
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {int} customer_comment_level 级别id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取评论标签成功",
     *       "alertMsg": "获取评论标签成功",
     *       "ret": {
     *          "id": "1",
     *          "customer_tag_name": "评价标签名称",
     *          "customer_comment_level": "评价等级",
     *           }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，"
     *       "ret": {}
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
            try {
                $level = CustomerCommentTag::getCommentTag($param['customer_comment_level']);

                $array = [];
                foreach ($level as $key => $val) {
                    unset($val['is_del']);
                    unset($val['is_online']);
                    $array[$key] = $val;
                }

                if (!empty($array)) {
                    $ret = ['commentTag' => $array];
                    return $this->send($ret, "获取评论标签成功", 1, 200, null, alertMsgEnum::getCommentLevelTagSuccess);
                } else {
                    return $this->send(null, "获取评论标签失败", 0, 200, null, alertMsgEnum::getCommentLevelTagFail);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-level-tag [GET] /user/get-level-tag （100%）
     * @apiDescription 获取评论的level和tag(郝建设)
     * @apiName actionGetLeveltag
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "获取标签和子标签成功",
     * "alertMsg": "获取标签和子标签成功",
     * "ret": [
     *     {
     *         "id": "1",
     *        "customer_comment_level": "0",
     *        "customer_comment_level_name": "满意",
     *        "tag": [
     *            {
     *                "id": "2",
     *                "customer_tag_name": "满意",
     *                "customer_comment_level": "0",
     *            },
     *            {
     *                "id": "6",
     *                "customer_tag_name": "满意",
     *                "customer_comment_level": "0",
     *            }
     *        ]
     *    },
     *    {
     *       "id": "2",
     *       "customer_comment_level": "1",
     *       "customer_comment_level_name": "一般",
     *       "tag": [
     *           {
     *               "id": "1",
     *               "customer_tag_name": "一般",
     *               "customer_comment_level": "1",
     *          },
     *          {
     *              "id": "5",
     *              "customer_tag_name": "一般",
     *              "customer_comment_level": "1",
     *          },
     *          {
     *              "id": "7",
     *              "customer_tag_name": "一般",
     *              "customer_comment_level": "1",
     *          }
     *      ]
     *  },
     *  {
     *      "id": "3",
     *     "customer_comment_level": "2",
     *     "customer_comment_level_name": "不满意",
     *     "tag": [
     *         {
     *             "id": "3",
     *             "customer_tag_name": "不满意",
     *             "customer_comment_level": "2",

     *         },
     *         {
     *             "id": "4",
     *             "customer_tag_name": "不满意",
     *             "customer_comment_level": "2",
     *         }
     *     ]
     * }
     * ]
     * }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret":{}
     *     }
     *
     */
    public function actionGetLevelTag()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            try {
                $level = CustomerCommentLevel::getCommentLevel();
                $array = array();
                foreach ($level as $key => $val) {
                    $levelTag = CustomerCommentTag::getCommentTag($val['customer_comment_level']);
                    foreach ($levelTag as $k => $v) {
                        $array[$v['customer_comment_level']] = $levelTag;
                    }
                }
                #合并数据组
                foreach ($level as $kk => $vv) {
                    foreach ($array as $kv => $vk) {
                        if ($vv['customer_comment_level'] == $kv) {
                            $level[$kk]['tag'] = $array[$kv];
                        }
                    }
                }

               
                foreach ($level as $key => $val) {
                    unset($val['is_del']);
                    $array[$key] = $val;
                }

                if (!empty($array)) {
                    return $this->send($array, "获取标签和子标签成功", 1, 200, null, alertMsgEnum::getLevelTagSuccess);
                } else {
                    return $this->send(null, "获取标签和子标签失败", 0, 200, null, alertMsgEnum::getLevelTagFail);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-comment-count [GET] /user/get-comment-count （100%）
     * @apiDescription 获取用户评价数量 郝建设()
     * @apiName actionGetCommentCount
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "获取用户评论数量成功",
     *       "alertMsg": "获取用户评论数量成功",
     *       "ret": {
     *          "CommentCount":"评论数量"
     *
     *           }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}
     *
     *     }
     *
     */
    public function actionGetCommentCount()
    {
        $param = Yii::$app->request->get();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            try {

                $level = CustomerComment::getCustomerCommentCount($customer->id);
                $ret['CommentCount'] = $level;

                return $this->send($ret, "获取用户评价数量", 1, 200, null, alertMsgEnum::getCommentCountSuccess);
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-goods [GET]  /user/get-goods （100%）
     * @apiDescription 获取给定经纬度范围内是否有该服务（郝建设）
     * @apiName actionGetGoods
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     * @apiParam {String} longitude 经度
     * @apiParam {String} latitude 纬度
     * @apiParam {int} order_service_type_id 服务id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "有该服务",
     *       "alertMsg": "有该服务",
     *       "ret": {}
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}
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
            try {
                $service = Order::getGoods($param['longitude'], $param['latitude'], $param['order_service_type_id']);
                if ($service) {
                    return $this->send(1, "该服务获取成功", 1, 200, null, alertMsgEnum::getGoodsSuccess);
                } else {
                    return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

    /**
     * @api {GET} /user/get-user-info [GET] /user/get-user-info （100%）
     * @apiDescription 通过token获取用户信息 (赵顺利)
     * @apiName actionGetUserInfo
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "code": 1,
     *      "msg": "获取用户信息成功",
     *      "ret": {
     *          "user": {
     *              "id": 1,
     *              "customer_name": 用户名,
     *              "customer_sex": 性别,
     *              "customer_birth": 生日,
     *              "customer_photo": 头像,
     *              "customer_phone": "电话",
     *              "customer_email": 邮箱,
     *              "operation_area_id": 商圈,
     *              "operation_area_name": 城市,
     *              "operation_city_id": 住址,
     *              "operation_city_name": 详细住址,
     *              "customer_level": 评级,
     *              "customer_complaint_times": 投诉,
     *              "customer_platform_version": 操作系统版本号,
     *              "customer_app_version": app版本号,
     *              "customer_mac": mac地址,
     *              "customer_login_ip": 登陆ip,
     *              "customer_login_time": 登陆时间,
     *              "customer_is_vip": 身份,
     *              "created_at": 创建时间,
     *              "updated_at": 更新时间,
     *          },
     *          "access_token": "bdf200df7b4afe39f6fe5110b98299bd"
     *      },
     *      "alertMsg": "获取用户信息成功"
     *  }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，",
     *       "alertMsg": "用户认证已经过期,请重新登录，",
     *       "ret": {}
     *
     *     }
     *
     */
    public function actionGetUserInfo()
    {
        $param = Yii::$app->request->get() or
                $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::getUserInfoFailed);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (empty($customer)) {
            return $this->send(null, "boss系统错误", 0, 1024, null, alertMsgEnum::getUserInfoFailed);
        }
        $ret = [
            "user" => $customer,
            "access_token" => $param['access_token']
        ];

        return $this->send($ret, "获取用户信息成功", 1, 200, null, alertMsgEnum::getUserInfoSuccess);
    }

    /**
     * @api {GET} /user/get-weixin-user-info  [GET] /user/get-weixin-user-info （90%）
     * @apiDescription 通过微信id获取用户信息 (赵顺利 未测试)
     * @apiName actionGetWeixinUserInfo
     * @apiGroup User
     *
     * @apiParam {String} weixin_id 微信id
     * @apiParam {String} sign 微信签名
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "code": 1,
     *      "msg": "获取用户信息成功",
     *      "ret": {
     *          "user": {
     *              "id": 1,
     *              "customer_name": 用户名,
     *              "customer_sex": 性别,
     *              "customer_birth": 生日,
     *              "customer_photo": 头像,
     *              "customer_phone": "电话",
     *              "customer_email": 邮箱,
     *              "operation_area_id": 商圈,
     *              "operation_area_name": 城市,
     *              "operation_city_id": 住址,
     *              "operation_city_name": 详细住址,
     *              "customer_level": 评级,
     *              "customer_complaint_times": 投诉,
     *              "customer_platform_version": 操作系统版本号,
     *              "customer_app_version": app版本号,
     *              "customer_mac": mac地址,
     *              "customer_login_ip": 登陆ip,
     *              "customer_login_time": 登陆时间,
     *              "customer_is_vip": 身份,
     *              "created_at": 创建时间,
     *              "updated_at": 更新时间,
     *          },
     *          "access_token": "bdf403df7b4afe39f6fe5110b98299bd"
     *      },
     *      "alertMsg": "获取用户信息成功"
     *  }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "微信id或签名不允许为空",
     *       "ret":{},
     *       "alertMsg": "获取用户信息失败"
     *     }
     *
     */
    public function actionGetWeixinUserInfo()
    {
        $param = Yii::$app->request->get();
        $weixin_id = $param['weixin_id'];
        $sign = $param['sign'];
        if (empty($weixin_id) || empty($sign)) {
            return $this->send(null, "微信id或签名不允许为空", 0, 403, null, alertMsgEnum::getUserInfoFailed);
        }

        $date = CustomerAccessToken::generateAccessTokenForWeixin($weixin_id, $sign);

        if ($date['errcode'] != '0') {
            return $this->send(null, $date['errmsg'], 0, 403, null, alertMsgEnum::getUserInfoFailed);
        }

        $ret = [
            "user" => $date['access_token'],
            "access_token" => $date['customer']
        ];
        return $this->send($ret, "获取用户信息成功", 1, 200, null, alertMsgEnum::getUserInfoSuccess);
    }

    /**
     * @api {POST} /user/get-user-feedback  [GET] /user/get-user-feedback （100%）
     * @apiDescription 用户意见反馈 (郝建设)
     * @apiName actionGetUserFeedback
     * @apiGroup User
     *
     * @apiParam {String} access_token     用户认证
     * @apiParam {String} [app_version]    访问源(android_4.2.2)
     * @apiParam {String} feedback_content 用户提交的数据
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "code": 1,
     *      "msg": "用户反馈信息提交成功",
     *      "ret": {},
     *      "alertMsg": "获取用户信息提交成功"
     *  }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户反馈信息提交失败",
     *       "ret":{},
     *       "alertMsg": "用户反馈信息提交失败"
     *     }
     *
     */
    public function actionGetUserFeedback()
    {
        $param = Yii::$app->request->post();

        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }

        #验证用户反馈提交
        if (empty($param['feedback_content'])) {
            return $this->send(null, "提交意见反馈不能为空", 0, 200, null, alertMsgEnum::UserFeedbackContent);
        }

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::getUserInfoFailed);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            try {
                $feedback = Customer::addFeedback($customer->id, $param['feedback_content']);
                if ($feedback) {
                    return $this->send(1, "获取用户信息提交成功", 1, 200, null, alertMsgEnum::getUserFeedback);
                } else {
                    return $this->send(null, "用户反馈信息提交失败", 0, 200, null, alertMsgEnum::getUserFeedbackFailure);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误" . $e, 0, 1024, null, alertMsgEnum::bossError);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 200, null, alertMsgEnum::userLoginFailed);
        }
    }

}
