<?php
namespace api\controllers;

use Yii;
use \core\models\customer\CustomerCode;
use \core\models\customer\CustomerAccessToken;
use \core\models\worker\WorkerAccessToken;
use \core\models\worker\Worker;
use \core\models\worker\WorkerCode;
class AuthController extends \api\components\Controller
{
    /**
     * @api {POST} /auth/login 客户登录（李勇100%）
     * @apiName Login
     * @apiGroup Auth
     *
     * @apiParam {String} phone 用户电话号码
     * @apiParam {String} verify_code 短信验证码
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object} user 用户信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "登录成功"，
     *       "ret":{
     *          "user":{}
     *          "access_token":""
     *       }
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "用户名或验证码错误"
     *     }
     */
    public function actionLogin()
    {
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['phone'])||!$param['phone']||!isset($param['verify_code'])||!$param['verify_code']){
                    return $this->send(null, "用户名或验证码不能为空", 0, 403);
        }
        $phone = $param['phone'];
        $verifyCode = $param['verify_code'];
        try{
            $checkRet = CustomerCode::checkCode($phone,$verifyCode);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if ($checkRet) {
            try{
                $token = CustomerAccessToken::generateAccessToken($phone, $verifyCode);
            }catch (\Exception $e) {
                return $this->send(null, "boss系统错误", 1024, 403);
            }
            if (empty($token)) {
                return $this->send($token, "生成token错误",0);
            }else{
                try{
                    $user = CustomerAccessToken::getCustomer($token);
                }catch (\Exception $e) {
                    return $this->send(null, "boss系统错误", 1024, 403);
                }
                $ret = [
                    "user" => $user,
                    "access_token" => $token
                ];
                return $this->send($ret, "登陆成功");
            }
        } else {
            return $this->send(null, "用户名或验证码错误", 0,403);
        }
    }

    /**
     * @api {POST} /auth/login-from-pop 客户登录(第三方渠道) (已实现)
     * @apiName LoginFromPop
     * @apiGroup Auth
     *
     * @apiParam {String} phone 用户电话号码
     * @apiParam {String} sign 签名
     * @apiParam {String} channel_name 渠道名称(拼音)
     *
     * @apiSuccess {Object} user 用户信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *       "msg": "登录成功"，
     *       "ret":{
     *          "user":{}
     *          "access_token":"807b62127fdc2554607a01529d9e4b7e"
     *       }
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "用户名,签名或渠道名称错误",
     *       "ret": null
     *     }
     */
    public function actionLoginFromPop()
    {
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['phone'])||!$param['phone']||!isset($param['sign'])||!$param['sign']||!isset($param['channel_name'])||!$param['channel_name']){
            return $this->send(null, "用户名,签名,渠道名称不能为空", 0, 403, "数据不完整");
        }
        $phone = $param['phone'];
        $sign = $param['sign'];
        $channel_name = $param['channel_name'];
        $checkRet = CustomerAccessToken::checkSign($phone, $sign, $channel_name);

        if ($checkRet) {
            $token = CustomerAccessToken::generateAccessTokenForPop($phone, $sign, $channel_name);
            if (empty($token)) {
                return $this->send(null, "生成token错误", 0);
            } else {
                $user = CustomerAccessToken::getCustomer($token);
                $ret = [
                    "user" => $user,
                    "access_token" => $token
                ];
                return $this->send($ret, "登陆成功");
            }
        } else {
            return $this->send(null, "用户名,签名或渠道名称错误", 0, 403);
        }
    }

    /**
     * @api {post} /auth/worker-login 阿姨登录（李勇100%)
     * @apiName actionWorkerLogin
     * @apiGroup Auth
     *
     * @apiParam {String} [phone] 阿姨电话号码
     * @apiParam {String} [verify_code] 短信验证码
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object} user 阿姨信息.
     * @apiSuccess {String} access_token 访问令牌字符串.
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "worker_name": "陈测试1",
     *          "worker_rule_id": 0,
     *          "worker_rule_description": "兼职",
     *          "worker_photo": "http://static.1jiajie.com/worker/face/1111116.jpg",
     *          "access_token": "hXjooooooooAPXzo5jjbMnz80dccYgwoooooooowoooooooo-1111116",
     *          "worker_id": "1111116",
     *          "shop_id": "68"
     *
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "用户名或验证码错误"
     *     }
     */
    public function actionWorkerLogin(){
	$param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['phone'])||!$param['phone']||!isset($param['verify_code'])||!$param['verify_code']){
                    return $this->send(null, "用户名或验证码不能为空", 0, 403);
        }
        $phone = $param['phone'];
        $verify_code = $param['verify_code'];
        //验证手机号
        if (!preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/", $phone)){
           return $this->send(null, "请输入正确的手机号", 0, 403);
        }
        try{
            $if_exist = Worker::getWorkerInfoByPhone($phone);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        if(empty($if_exist)){
             return $this->send(null, "该用户不存在", 0, 403);
        }
        try{
             $checkRet = WorkerCode::checkCode($phone,$verify_code);
        }catch (\Exception $e) {
             return $this->send(null, "boss系统错误", 1024, 403);
        }
        if($checkRet){
            try{
                 $token = WorkerAccessToken::generateAccessToken($phone,$verify_code);
            }catch (\Exception $e) {
                 return $this->send(null, "boss系统错误", 1024, 403);
            }
            if (empty($token)) {
                 return $this->send(null, "生成token错误",0);
            }else{
                 $user = WorkerAccessToken::getWorker($token);
                 $ret = [
                     "user" => $user,
                     "access_token" => $token
                 ];
                 return $this->send($ret, "登陆成功",1);
            }
            
        } else {
            return $this->send(null, "用户名或验证码错误", 0,403);
        }
    }

}

?>