<?php
namespace api\controllers;
use Yii;
use \core\models\customer\CustomerCode;
use \core\models\customer\CustomerAccessToken;
class AuthController extends \api\components\Controller
{


    /**
     *
     * @api {POST} /auth/login 客户登录
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
     *       "code": "ok",
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
     *       "code":"error",
     *       "msg": "用户名或验证码错误"
     *     }
     */
    public function actionLogin()
    {
        $phone = Yii::$app->request->post('phone');
        $verifyCode = Yii::$app->request->post('verify_code');
        if(empty($phone)||empty($verifyCode)){
            return $this->send(null, "用户名或验证码不能为空", "error",403,"数据不完整");
        }
        $checkRet = CustomerCode::checkCode($phone,$verifyCode);
        if ($checkRet) {
            $token = CustomerAccessToken::generateAccessToken($phone, $verifyCode);
            if (empty($token)) {
                return $this->send(null, "生成token错误","error");
            }else{
                $user = CustomerAccessToken::getCustomer($token);
                $ret = [
                    "user" => $user,
                    "access_token" => $token
                ];
                return $this->send($ret, "登陆成功");
            }
        } else {
            return $this->send(null, "用户名或验证码错误", "error",403);
        }
    }

    /**
     * @api {get} /mobileapidriver2/driver_login 阿姨登录
     * @apiName actionDriverLogin
     * @apiGroup Auth
     *
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} username  用户名.
     * @apiParam {String} Password   密码.
     * @apiParam {String} client_version 版本号.
     * @apiParam {String} version_name 版本名.
     * @apiParam {String} macAdd  机器码.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "driverName": "陈测试1",
     *          "isFullTime": 0,
     *          "fullTimeName": "兼职",
     *          "driverHeadUrl": "http://static.1jiajie.com/worker/face/1111116.jpg",
     *          "session_id": "hXjooooooooAPXzo5jjbMnz80dccYgwoooooooowoooooooo-1111116",
     *          "worker_id": "1111116",
     *          "shop_id": "68",
     *          "result": "1",
     *          "msg": ""
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */

}

?>