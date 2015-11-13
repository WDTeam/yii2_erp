<?php
namespace restapi\controllers;

use Yii;
use \core\models\customer\CustomerCode;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\OperationOrderChannel;
use \core\models\worker\WorkerAccessToken;
use \core\models\worker\Worker;
use \core\models\worker\WorkerCode;
use \restapi\models\alertMsgEnum;
use core\models\worker\WorkerIdentityConfig;

class AuthController extends \restapi\components\Controller
{
    /**
     * @api {POST} /auth/login Login [POST] /auth/login Login（100%）
     *
     * @apiDescription  客户登录（李勇）
     * @apiName actionLogin
     * @apiGroup Auth
     *
     * @apiParam {String} phone 用户电话号码
     * @apiParam {String} verify_code 短信验证码
     * @apiParam {String} platform_version app版本
     *
     * @apiSuccess {Object} user 用户信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 1,
     *   "msg": "登陆成功",
     *   "ret": {
     *       "user": {
     *           "id": "ID",
     *           "customer_name": "用户名",
     *           "customer_sex": "性别",
     *           "customer_birth": "生日",
     *           "customer_photo": "头像",
     *           "customer_phone": "电话",
     *           "customer_email": "邮箱",
     *           "operation_area_id": "商圈id",
     *           "operation_area_name": "商圈",
     *           "operation_city_id": "城市id",
     *           "operation_city_name": "城市",
     *           "customer_level": "评级",
     *           "customer_complaint_times": "投诉",
     *           "customer_platform_version": "操作系统版本号",
     *           "customer_app_version": "app版本号",
     *           "customer_mac": "mac地址",
     *           "customer_login_ip": "登陆ip",
     *           "customer_login_time": "登陆时间",
     *           "customer_is_vip": "身份",
     *           "created_at": "创建时间",
     *           "updated_at": "更新时间"
     *           },
     *       "access_token": "token值"
     *        },
     *   "alertMsg": "登陆成功"
     *    }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *   {
     *      "code": 0,
     *      "msg": "用户名或验证码错误",
     *      "ret": {},
     *      "alertMsg": "登陆失败"
     *    }
     */
    public function actionLogin()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['phone']) || !$param['phone'] || !isset($param['verify_code']) || !$param['verify_code']) {
            return $this->send(null, "用户手机号或验证码不能为空", 403, 200, null, alertMsgEnum::customerLoginDataDefect);
        }
        if (!isset($param['platform_version']) || !$param['platform_version'] ) {
            return $this->send(null, "用户渠道不能为空", 403, 200, null, alertMsgEnum::customerLoginDataDefectPlatform);
        }
        $phone = $param['phone'];
        $verifyCode = $param['verify_code'];
        $platform_version = $param['platform_version'];
        try {
            $channal_id=OperationOrderChannel::get_post_id($platform_version);
        } catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if(empty($channal_id)){
            return $this->send(null,"数据库中没有此渠道", 1024, 403, null, alertMsgEnum::bossError);
        }
        try {
            $checkRet = CustomerCode::checkCode($phone, $verifyCode);
        } catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if ($checkRet) {
            try {
                $token = CustomerAccessToken::generateAccessToken($phone, $verifyCode,$channal_id);
            } catch (\Exception $e) {
                return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
            }
            if (empty($token)) {
                return $this->send(null, "生成token错误", 0, 403, null, alertMsgEnum::customerLoginFail);
            } else {
                try {
                    $user = CustomerAccessToken::getCustomer($token);
                } catch (\Exception $e) {
                    return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
                }
                $ret = [
                    "user" => $user,
                    "access_token" => $token
                ];
                return $this->send($ret, "登陆成功", 1, 200, null, alertMsgEnum::customerLoginSuccess);

            }
        } else {
            return $this->send(null, "用户名或验证码错误", 0, 403, null, alertMsgEnum::customerLoginFail);
        }
    }

    /**
     * @api {POST} /auth/login-from-pop [POST] /auth/login-from-pop(100%)
     * @apiDescription 客户登录(第三方渠道)
     * @apiName actionLoginFromPop
     * @apiGroup Auth
     *
     * @apiParam {String} phone 用户电话号码
     * @apiParam {String} sign 签名
     * @apiParam {String} channel_id 渠道id
     *
     * @apiSuccess {Object} user 用户信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     * 
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "登录成功"，
     *       "ret":{
     *          "user":{}
     *          "access_token":"807b62127fdc2554607a01529d9e4b7e"
     *       },
     *       "alertMsg": "登陆成功"
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "用户名,签名或渠道id错误",
     *       "ret": null,
     *       "alertMsg": "用户名,签名或渠道id错误" 
     *     }
     */
    public function actionLoginFromPop()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['phone']) || !$param['phone'] || !isset($param['sign']) || !$param['sign'] || !isset($param['channel_id']) || !$param['channel_id']) {
            return $this->send(null, "用户名,签名,渠道id不能为空", 0, 403,null,alertMsgEnum::loginFromPopNoPhone);
        }
        $phone = $param['phone'];
        $sign = $param['sign'];
        $channel_id = $param['channel_id'];
        try {
            $checkRet = CustomerAccessToken::checkSign($phone, $sign, $channel_id);
        } catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if ($checkRet) {
            try {
                $token = CustomerAccessToken::generateAccessTokenForPop($phone, $sign, $channel_id);
            } catch (\Exception $e) {
                return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
            }
            if($token['response'] == 'error'){
                    return $this->send(null, $token['errmsg'], 0, 403,null,alertMsgEnum::loginFromPopFail);
            }else{
                $access_token = $token['access_token'];
                try {
                    $user = CustomerAccessToken::getCustomer($access_token);
                } catch (\Exception $e) {
                    return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
                }
                $ret = [
                    "user" => $user,
                    "access_token" => $access_token
                ];
                return $this->send($ret, "登陆成功",1, 200,null,alertMsgEnum::loginFromPopSuccess);
            }
        } else {
            return $this->send(null, "用户名,签名或渠道id错误", 0, 403,null,alertMsgEnum::loginFromPopFail);
        }
    }

    /**
     * @api {POST} /auth/worker-login [POST] /auth/worker-login（李勇100%)
     * @apiDescription 阿姨登录（李勇）
     * @apiName actionWorkerLogin
     * @apiGroup Auth
     *
     * @apiParam {String} phone 阿姨电话号码
     * @apiParam {String} verify_code 短信验证码
     * @apiParam {String} platform_version app版本
     *
     * @apiSuccess {Object} user 阿姨信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *{
     *       "code": 1,
     *       "msg": "登陆成功",
     *       "ret": {
     *           "user": {
     *               "id": "阿姨表自增id",
     *               "shop_id": "门店id",
     *               "worker_name": "阿姨姓名",
     *               "worker_phone": "阿姨手机",
     *               "worker_photo": "阿姨头像地址",
     *               "worker_level": "阿姨等级",
     *               "worker_auth_status": "阿姨审核状态 0新录入1已审核2已基础培训3已试工4已上岗5已晋升培训",
     *               "worker_work_city": "阿姨工作城市",
     *               "worker_work_area": "阿姨工作区县",
     *               "worker_work_street": "阿姨常用工作地址",
     *               "worker_work_lng": "阿姨常用工作经度",
     *               "worker_work_lat": "阿姨常用工作纬度",
     *               "worker_star": "阿姨星级",
     *               "worker_type": "阿姨类型 1自有 2非自有",
     *               "worker_rule_id": "阿姨角色id ",
     *               "worker_identity_id": "阿姨身份id ",
     *               "worker_is_block": "阿姨是否封号 0正常1封号",
     *               "worker_is_vacation": "阿姨是否请假 0正常1请假中",
     *               "worker_is_blacklist": "阿姨是否黑名单 0正常1黑名单",
     *               "worker_blacklist_reason": "阿姨被加入黑名单的原因",
     *               "worker_blacklist_time": "阿姨加入黑名单的原因",
     *               "worker_is_dimission": "阿姨离职原因",
     *               "worker_dimission_reason": "阿姨离职原因",
     *               "worker_dimission_time": "阿姨离职时间",
     *               "created_ad": "阿姨录入时间",
     *               "updated_ad": "最后更新时间"
     *           },
     *           "access_token": "token值"
     *       },
     *      "alertMsg": "登陆成功" 
     *   }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *    {
     *    "code": 0,
     *    "msg": "用户名或验证码错误",
     *    "ret": {},
     *    "alertMsg": "登陆失败"
     *    }
     */
    public function actionWorkerLogin()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['phone']) || !$param['phone'] || !isset($param['verify_code']) || !$param['verify_code']) {
            return $this->send(null, "用户名或验证码不能为空", 0, 403, null, alertMsgEnum::workerLoginDataDefect);
        }
        $phone = $param['phone'];
        $verify_code = $param['verify_code'];
        //验证手机号
        if (!preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/", $phone)) {
            return $this->send(null, "请输入正确手机号", 0, 403, null, alertMsgEnum::workerLoginWrongPhoneNumber);
        }
        try {
            $login_info = Worker::checkWorkerLogin($phone);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (!empty($login_info)) {
            if ($login_info['can_login'] == 0 && $login_info['login_type'] == 1) {
                return $this->send(null, "不存在该阿姨", 0, 403, null, alertMsgEnum::workerLoginNoWorker);
            } elseif ($login_info['can_login'] == 0 && $login_info['login_type'] == 2) {
                return $this->send(null, "阿姨已在黑名单", 0, 403, null, alertMsgEnum::workerLoginIsBlackList);
            } elseif ($login_info['can_login'] == 0 && $login_info['login_type'] == 3) {
                return $this->send(null, "阿姨已离职", 0, 403, null, alertMsgEnum::workerLoginIsDimission);
            } elseif ($login_info['can_login'] == 0 && $login_info['login_type'] == 4) {
                return $this->send(null, "阿姨已删除", 0, 403, null, alertMsgEnum::workerLoginIsDel);
            }
        } else {
            return $this->send(null, "验证阿姨是否为系统用户系统错误", 0, 403, null, alertMsgEnum::workerLoginFail);
        }
        try {
            $checkRet = WorkerCode::checkCode($phone, $verify_code);
        } catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if ($checkRet) {
            try {
                $token = WorkerAccessToken::generateAccessToken($phone, $verify_code);
            } catch (\Exception $e) {
                return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
            }
            if (empty($token)) {
                return $this->send(null, "生成token错误", 0, 403, null, alertMsgEnum::workerLoginFail);
            } else {
                $user = WorkerAccessToken::getWorker($token)->getAttributes();
                $user['worker_identity_description'] = WorkerIdentityConfig::getWorkerIdentityShow($user['worker_identity_id']);
                $ret = [
                    "user" => $user,
                    "access_token" => $token
                ];
                return $this->send($ret, "登陆成功", 1, 200, null, alertMsgEnum::workerLoginSuccess);
            }

        } else {
            return $this->send(null, "用户名或验证码错误", 0, 403, null, alertMsgEnum::workerLoginFail);
        }
    }

    /**
     * @api {POST} /auth/weixin-login WeixinLogin（90%）
     * @apiName actionWeixinLogin
     * @apiGroup Auth
     * @apiDescription  微信用户登录（赵顺利 未测试）
     *
     * @apiParam {String} phone 用户电话号码
     * @apiParam {String} verify_code 短信验证码
     * @apiParam {String} weixin_id 微信id
     * @apiParam {String} platform_version app版本
     *
     * @apiSuccess {Object} user 用户信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *   "code": 1,
     *   "msg": "登陆成功",
     *   "ret": {
     *       "user": {
     *           "id": "ID",
     *           "customer_name": "用户名",
     *           "customer_sex": "性别",
     *           "customer_birth": "生日",
     *           "customer_photo": "头像",
     *           "customer_phone": "电话",
     *           "customer_email": "邮箱",
     *           "operation_area_id": "商圈id",
     *           "operation_area_name": "商圈",
     *           "operation_city_id": "城市id",
     *           "operation_city_name": "城市",
     *           "customer_level": "评级",
     *           "customer_complaint_times": "投诉",
     *           "customer_platform_version": "操作系统版本号",
     *           "customer_app_version": "app版本号",
     *           "customer_mac": "mac地址",
     *           "customer_login_ip": "登陆ip",
     *           "customer_login_time": "登陆时间",
     *           "customer_is_vip": "身份",
     *           "created_at": "创建时间",
     *           "updated_at": "更新时间"
     *           },
     *       "access_token": "token值"
     *        },
     *   "alertMsg": "登陆成功"
     *    }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *   {
     *      "code": 0,
     *      "msg": "用户名或验证码错误",
     *      "ret": {},
     *      "alertMsg": "登陆失败"
     *    }
     */
    public function actionWeixinLogin()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (empty($param['phone']) || empty($param['verify_code']) || empty($param['weixin_id'])) {
            return $this->send(null, "参数不完整，登录失败。", 0, 403, null, alertMsgEnum::uesrWeiXinLoginFailed);
        }
        $phone = $param['phone'];
        $verifyCode = $param['verify_code'];
        $weixin_id = $param['weixin_id'];

        try {
            $date = CustomerAccessToken::createWeixinCustomer($phone, $verifyCode, $weixin_id);
            if($date['errcode']=='0')
            {
                $ret = [
                    "user" => $date['customer'],
                    "access_token" => $date['access_token']
                ];
                return $this->send($ret, "登陆成功", 1, 200, null, alertMsgEnum::uesrWeiXinLoginSuccess);
            }
            else
            {
                return $this->send(null, $date['errmsg'], 0, 403, null, alertMsgEnum::uesrWeiXinLoginFailed);
            }
        } catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403, null, alertMsgEnum::uesrWeiXinLoginFailed);
        }
    }

}

?>