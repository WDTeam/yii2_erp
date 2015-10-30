<?php

namespace restapi\controllers;

use \core\models\customer\Customer;
use Yii;
use \core\models\customer\CustomerAddress;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
use \core\models\operation\coupon\Coupon;
use \core\models\customer\PaymentCustomerTransRecord;
use \core\models\customer\CustomerExtBalance;
use \core\models\order\Order;
use \core\models\customer\CustomerComment;
use \core\models\comment\CustomerCommentTag;
use \core\models\comment\CustomerCommentLevel;
use \core\models\customer\CustomerExtScore;

class UserController extends \restapi\components\Controller
{

    /**
     * @api {POST} v1/user/add-address 添加常用地址 (已完成100%) 
     *
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
     *       "msg": "地址添加成功"
     *       "ret":{
     *       "address":
     *          {
     *          "id": 主键,
     *          "customer_id":关联客户,
     *          "operation_province_id": 110000,
     *          "operation_city_id": 市,
     *          "operation_area_id": 区,
     *          "operation_province_name": "北京",
     *          "operation_city_name": "北京市",
     *          "operation_area_name": "朝阳区",
     *          "operation_province_short_name": "北京",
     *          "operation_city_short_name": "北京",
     *          "operation_area_short_name": "朝阳",
     *          "customer_address_detail": "详细地址",
     *          "customer_address_status": 1,客户地址类型,1为默认地址，0为非默认地址
     *          "customer_address_longitude": 经度,
     *          "customer_address_latitude": 纬度,
     *          "customer_address_nickname": "用户昵称",
     *          "customer_address_phone": "被服务者手机",
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

        //控制添加地址时手机号码必须填写
        if (empty($param['customer_address_phone']) || empty($param['customer_address_nickname'])) {
            return $this->send(null, "被服务者手机或被服务者昵称不能为空", 0, 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (!empty($customer) && !empty($customer->id)) {
            $model = CustomerAddress::addAddress($customer->id, @$param['operation_province_name'], @$param['operation_city_name'], @$param['operation_area_name'], @$param['customer_address_detail'], @$param['customer_address_nickname'], @$param['customer_address_phone']);

            if (!empty($model)) {
                $ret = ['address' => $model];
                return $this->send($ret, "常用地址添加成功", 1);
            } else {
                return $this->send(null, "常用地址添加失败", 0, 403);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-addresses 常用地址列表 (已完成100%)
     *
     * @apiName actionGetAddresses
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
            return $this->send($ret, "获取地址列表成功", 1);
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * @api {DELETE} v1/user/delete-address 删除用户常用地址 (已完成100%) 
     *
     * @apiName actionDeleteAddress
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
            return $this->send(null, "删除成功", 1);
        } else {
            return $this->send(null, "删除失败", 0, 403);
        }
    }

    /**
     * @api {PUT} v1/user/set-default-address 设置默认地址 (已完成100%) 
     * @apiDescription 用户每次下完单都会将该次地址设置为默认地址，下次下单优先使用默认地址
     * @apiName actionSetDefaultAddress
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
        $params = json_decode(Yii::$app->request->getRawBody(), true);
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
        try {
            if (CustomerAddress::updateAddress($model->id, $model->operation_province_name, $model->operation_city_name, $model->operation_area_name, $model->customer_address_detail, $model->customer_address_nickname, $model->customer_address_phone)
            ) {
                return $this->send(null, "设置默认地址成功", 1);
            } else {

                return $this->send(null, "设置默认地址失败", 0, 403);
            }
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 0, 1024);
        }
    }

    /**
     * @api {PUT} v1/user/update-address 修改常用地址 (已完成100%) 
     *
     * @apiName actionUpdateAddress
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
        $params = json_decode(Yii::$app->request->getRawBody(), true);
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

        try {
            if (CustomerAddress::updateAddress($model->id, @$params['operation_province_name'], @$params['operation_city_name'], @$params['operation_area_name'], @$params['customer_address_detail'], @$params['customer_address_nickname'], @$params['customer_address_phone'])
            ) {
                return $this->send(null, "修改常用地址成功", 1);
            } else {

                return $this->send(null, "修改常用地址失败", 0, 403);
            }
        } catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 0, 1024);
        }
    }

    /**
     * @api {GET} v1/user/default-address 获取默认地址 (赵顺利100%)
     * @apiName actionDefaultAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "修改常用地址成功"
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
     * @apiError AddressNotFound 地址信息获取失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "error",
     *       "msg": "地址信息获取失败"
     *
     *     }
     */
    public function actionDefaultAddress()
    {
        $params = Yii::$app->request->get() or $params = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty($params['access_token']) || !CustomerAccessToken::checkAccessToken($params['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $customer = CustomerAccessToken::getCustomer($params['access_token']);

        if (!empty($customer) && !empty($customer->id)) {

            try {
                $Address = CustomerAddress::getCurrentAddress($customer->id);
                if (empty($Address)) {
                    return $this->send(null, "该用户没有默认地址", 0, 403);
                }
                $ret = ['address' => $Address];
                return $this->send($ret, "获取默认地址成功", 1);
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "获取用户信息失败", "error", 403);
        }
    }

    /**
     * @api {DELETE} v1/user/delete-used-worker 删除常用阿姨 （功能已经实现,需再次核实 100%）
     *
     *
     * @apiName actionDeleteUsedWorker
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
             * @param $customer ->id int 用户id
             * @param $worker      int 阿姨id
             * @param $type        int 标示类型，1时判断黑名单阿姨，0时判断常用阿姨
             */
            $deleteData = \core\models\customer\CustomerWorker::deleteWorker(1, 2, 1);
            if ($deleteData) {
                $deleteData = array(1);
                return $this->send($deleteData, "删除成功", 1);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {GET} v1/user/black-list-workers 黑名单阿姨列表 （功能已经完成,需要核实传递参数和返回数据格式 已完成100%）
     * @apiDescription 获得该用户添加进黑名单的阿姨
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
             * @param $customer ->id int 用户id
             * @param $is_block      int 阿姨id
             */
            $workerData = \core\models\customer\CustomerWorker::blacklistworkers(1, 1);
            if ($workerData) {
                return $this->send($workerData, "阿姨列表查询", 1);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {DELETE} v1/user/remove-worker 移除黑名单中的阿姨 （功能已经实现,需要再次确认传递参数 已完成100%）
     *
     *
     * @apiName actionRemoveWorker
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
                return $this->send($deleteData, "移除成功", 1);
            } else {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            }
        }
    }

    /**
     * @api {GET} v1/user/get-user-money 用户余额和消费记录 （郝建设 已完成99% ;）
     * 
     *
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
     * "is_del"：'删除',
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

            try {
                /**
                 * 获取客户余额
                 *
                 * @param int $customer 用户id
                 */
                $userBalance = CustomerExtBalance::getCustomerBalance($customer->id);
                if (!$userBalance) {
                    $userBalance = '0';
                }
                /**
                 * 获取用户消费记录
                 *
                 * @param int $customer 用户id
                 */
                $userRecord = PaymentCustomerTransRecord::queryRecord($customer->id);

                $ret["userBalance"] = $userBalance;
                $ret["userRecord"] = $userRecord;
                return $this->send($ret, "查询成功", 1);
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }

            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-user-score 用户积分明细 （功能已实现,不明确需求端所需字段格式 90%）
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
     * "ret": {
     * "scoreCategory": [
     *      {
     *       "id": "1",
     *       "customer_id": "客户",
     *       "customer_score": "客户积分",
     *       "created_at": "创建时间",
     *       "updated_at": "更新时间",
     *       "is_del": 是否删除
     *       }
     *      ]
     *     }
     *    }

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

        @$app_version = $param['app_version']; #版本

        if (empty($param['access_token']) || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "0", 403);
        }

        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        if (!empty($customer) && !empty($customer->id)) {
            try {
                /**
                 * @param int $customer_id 用户id
                 */
                $userscore = CustomerExtScore::getCustomerScoreList($customer->id);
                if ($userscore) {
                    $ret["scoreCategory"] = $userscore;
                    return $this->send($ret, "用户积分明细列表", 1);
                } else {
                    return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        }
    }

    /**
     * @api {POST} v1/user/user-suggest 用户评价 （郝建设 100%）
     *
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

        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (empty($param['order_id']) || empty($param['customer_comment_phone'])) {
            return $this->send(null, "提交参数中缺少必要的参数.", 0, 403);
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

        if (!empty($customer) && !empty($customer->id)) {
            try {
                $param['customer_id'] = $customer->id;
                $model = CustomerComment::addUserSuggest($param);
                if (!empty($model)) {
                    return $this->send([1], "添加评论成功", 1);
                } else {
                    return $this->send(null, "添加评论失败", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-comment-level 获取用户评价等级 （郝建设 100%）
     *
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
            try {
                $level = CustomerCommentLevel::getCommentLevel();
                if (!empty($level)) {
                    $ret = ['comment' => $level];
                    return $this->send($ret, "获取评论级别成功", 1);
                } else {
                    return $this->send(null, "获取评论级别失败", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-comment-level-tag 获取用户评价等级下面的标签 （郝建设 100%）
     *
     * @apiName actionGetCommentLevelTag
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
            try {
                $level = CustomerCommentTag::getCommentTag($param['customer_comment_level']);

                if (!empty($level)) {
                    $ret = ['commentTag' => $level];
                    return $this->send($ret, "获取评论标签成功", 1);
                } else {
                    return $this->send(null, "获取评论标签失败", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-level-tag 获取评论的level和tag （郝建设 100%）
     *
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
     * "ret": [
     *     {
     *         "id": "1",
     *        "customer_comment_level": "0",
     *        "customer_comment_level_name": "满意",
     *        "is_del": "0",
     *        "tag": [
     *            {
     *                "id": "2",
     *                "customer_tag_name": "满意",
     *                "customer_comment_level": "0",
     *                "is_online": "0",
     *                "is_del": "0"
     *            },
     *            {
     *                "id": "6",
     *                "customer_tag_name": "满意",
     *                "customer_comment_level": "0",
     *                "is_online": "0",
     *                "is_del": "0"
     *            }
     *        ]
     *    },
     *    {
     *       "id": "2",
     *       "customer_comment_level": "1",
     *       "customer_comment_level_name": "一般",
     *       "is_del": "0",
     *       "tag": [
     *           {
     *               "id": "1",
     *               "customer_tag_name": "一般",
     *               "customer_comment_level": "1",
     *               "is_online": "1",
     *               "is_del": "0"
     *          },
     *          {
     *              "id": "5",
     *              "customer_tag_name": "一般",
     *              "customer_comment_level": "1",
     *              "is_online": "0",
     *              "is_del": "0"
     *          },
     *          {
     *              "id": "7",
     *              "customer_tag_name": "一般",
     *              "customer_comment_level": "1",
     *              "is_online": "0",
     *              "is_del": "0"
     *          }
     *      ]
     *  },
     *  {
     *      "id": "3",
     *     "customer_comment_level": "2",
     *     "customer_comment_level_name": "不满意",
     *     "is_del": "0",
     *     "tag": [
     *         {
     *             "id": "3",
     *             "customer_tag_name": "不满意",
     *             "customer_comment_level": "2",
     *             "is_online": "0",
     *             "is_del": "0"
     *         },
     *         {
     *             "id": "4",
     *             "customer_tag_name": "不满意",
     *             "customer_comment_level": "2",
     *             "is_online": "0",
     *             "is_del": "0"
     *         }
     *     ]
     * }
     * ]
     * }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "0",
     *       "msg": "用户认证已经过期,请重新登录，"
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

                if (!empty($level)) {
                    return $this->send($level, "获取标签和子标签成功", 1);
                } else {
                    return $this->send(null, "获取标签和子标签失败", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-comment-count 获取用户评价数量 （郝建设 100%）
     *
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
     *       "ret": {
     *          "CommentCount":"评论数量"
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

                return $this->send($ret, "获取用户评价数量", 1);
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录.", 0, 403);
        }
    }

    /**
     * @api {GET} v1/user/get-goods 获取给定经纬度范围内是否有该服务 （郝建设 100%）
     *
     * @apiName actionGetGoods
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
            try {
                $service = Order::getGoods($param['longitude'], $param['latitude'], $param['order_service_type_id']);
                if ($service) {
                    return $this->send(1, "该服务获取成功", 1);
                } else {
                    return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
                }
            } catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 0, 1024);
            }
        } else {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
    }

}