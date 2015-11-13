<?php
namespace restapi\controllers;

use Yii;
use \core\models\customer\CustomerCode;
use \core\models\worker\Worker;
use \core\models\worker\WorkerCode;
use restapi\models\alertMsgEnum;

class SendSmsController extends \restapi\components\Controller
{
    /**
     * @api {GET} /send-sms/send-v [GET ] /send-sms/send-v
     * @apiDescription 发短消息
     * @apiName actionSendV
     * @apiGroup SendSms
     *
     * @apiParam {String} platform_version      版本号
     * @apiParam {Number} telephone 电话
     * @apiParam {Mixed} message 发送消息
     *
     * @apiSuccess {String} code 返回码 ok.
     * @apiSuccess {Array} msg 返回消息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "result": "1",
     *       "msg": "ok",
     *       "tel": "15011152243",
     *       "smsTxt": "您已成功预约e家洁服务，下载应用（http://t.cn/8schPc6）可随时跟踪订单状态、查看服务人员详情，如有问题可致电唯一客服热线4006767636，祝您生活愉快！"
     *     }
     *
     * @apiSampleRequest http://test.web.1jiajie.com/mobileapinew8/packageSmsSendSms?platform_version=apitest&tel=15011152243&msg%5BtempId%5D=1
     */
    public function actionSendV()
    {
        $content = true;
        if ($content) {
            $id = $content['id'];
            $token = Yii::$app->cache->get($id);
            if ($token == false) {
                $token = base64_encode($id . time());
                Yii::$app->cache->set($id, $token, Yii::$app->params['cacheExpiration']);
                Yii::$app->cache->set($token, $id, Yii::$app->params['cacheExpiration']);
            }
            $ret = [
                "user" => $content,
                "access_token" => $token
            ];

            return $this->send($ret, "登陆成功", 1, 200,null, alertMsgEnum::sendVSuccess);
        } else {
            return $this->send(null, "用户名或验证码错误", 0, 403,null, alertMsgEnum::sendVFail);
        }
    }

    /**
     * @api {GET} /send-sms/send-message-code [GET] /send-sms/send-message-code (100%)
     * @apiName actionSendMessageCode
     * @apiGroup SendSms
     * @apiDescription 请求向用户手机发送验证码用于登录(赵顺利)
     * @apiParam {String} phone 用户手机号
     * @apiParam {String} platform_version      版本号
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "code":"1"
     *        "msg": "短信发送成功"
     *     }
     *
     * @apiError PhoneNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "电话号码不符合规则"
     *     }
     */
    public function actionSendMessageCode()
    {
        @$phone = Yii::$app->request->get('phone');
        @$app_version = Yii::$app->request->get('app_version');
        if (preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[0-9]|18[0-9]|14[57])[0-9]{8}$/", $phone)) {
            //验证通过
            if (!CustomerCode::generateAndSend($phone)) {
                return $this->send(null, "短信发送失败", 0, 403,null,alertMsgEnum::sendUserCodeFailed);
            }
//加入发送验证码失败时的不同提示
//            $customer_code=CustomerCode::generateAndSend($phone);
//            if($customer_code['success']==2){
//                return $this->send(null, "验证码发送超过5次", 0, 403,null,alertMsgEnum::sendUserCodeOverFive);
//            }elseif($customer_code['success']==3){
//                return $this->send(null, "验证码发送频率过高（60s）", 0, 403,null,alertMsgEnum::sendUserCodeSixty);
//            }
        } else {
            return $this->send(null, "电话号码不符合规则", 0, 403,null,alertMsgEnum::sendUserCodeFailed);

        }
        return $this->send(null, "短信发送成功", 1, 200,null, alertMsgEnum::sendUserCodeSuccess);
    }

    /**
     * @api {GET} /send-sms/send-worker-message-code  [GET]Send-Worker-Message-Code（100%）
     * 
     * @apiDescription 请求向阿姨手机发送验证码用于登录（李勇）
     * @apiName actionSendWorkerMessageCode
     * @apiGroup SendSms
     * @apiParam {String} phone 用户手机号
     * @apiParam {String} platform_version      版本号
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "code": 1,
     *           "msg": "短信发送成功",
     *           "ret": {},
     *           "alertMsg": "验证码已发送手机，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636"
     *        }
     *
     * @apiError PhoneNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *      {
     *          "code": 0,
     *          "msg": "阿姨不存在或在黑名单或离职或删号",
     *          "ret": {},
     *          "alertMsg": "验证码已发失败"
     *      }
     * 
     */
    public function actionSendWorkerMessageCode()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['phone']) || !$param['phone']) {
            return $this->send(null, "手机号码不能为空", 403, 200, null, alertMsgEnum::sendWorkerCodeFaile);
        }
        if (!isset($param['platform_version']) || !$param['platform_version']) {
            return $this->send(null, "访问源不能为空", 403, 200, null, alertMsgEnum::sendWorkerCodeFaile);
        }
        $phone =$param['phone'];
        $platform_version = $param['platform_version'];
        if (preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/", $phone)) {
            $login_info = Worker::checkWorkerLogin($phone);
            $whether_send_code = WorkerCode::whetherSendCode($phone);
            if($whether_send_code==1){
                return $this->send(null, "短信发送频率过高（60s）", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);
            }elseif($whether_send_code==2){
                return $this->send(null, "今日短信发送超过5次", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);
            }
            if($login_info['can_login']==1){
                //验证通过
                if (!WorkerCode::generateAndSend($phone)) {
                    return $this->send(null, "短信发送失败", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);
                }else{
                    return $this->send(null, "短信发送成功",1,200,null,alertMsgEnum::sendWorkerCodeSuccess);
                }
            }else{
                 return $this->send(null, "阿姨不存在或在黑名单或离职或删号", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);
            }
        } else {
            return $this->send(null, "电话号码不符合规则", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);

        }
        return $this->send(null, "短信发送失败", 0, 403,null,alertMsgEnum::sendWorkerCodeFaile);
    }
    
    
    /**
     * @api {GET} /send-sms/voice-verify-code  [GET]/send-sms/voice-verify-code
     * 
     * @apiDescription  语音验证码发送
     * @apiName actionVoiceVerifyCode
     * @apiGroup SendSms
     * @apiParam {String} phone 手机号
     * @apiParam {String} platform_version 访问源
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "code": 1,
     *           "msg": "语音验证码发送成功",
     *           "ret": {},
     *           "alertMsg": "语音验证码已发送手机，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636"
     *        }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "电话号码不符合规则",
     *       "ret": {},
     *       "alertMsg": "电话号码不符合规则"
     *     }
     */
    public function actionVoiceVerifyCode()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['phone']) || !$param['phone']) {
            return $this->send(null, "手机号码不能为空", 403, 200, null, alertMsgEnum::voiceVerifyCodeFail);
        }
        if (!isset($param['platform_version']) || !$param['platform_version']) {
            return $this->send(null, "访问源不能为空", 403, 200, null, alertMsgEnum::voiceVerifyCodeFail);
        }
        $phone =$param['phone'];
        $platform_version = $param['platform_version'];
        if (preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/", $phone)) {
             return $this->send(null, "语音验证码发送成功",1,200,null,alertMsgEnum::voiceVerifyCodeSuccess);
            
        } else {
            return $this->send(null, "电话号码不符合规则", 0, 403,null,alertMsgEnum::voiceVerifyCodeFail);

        }
        return $this->send(null, "语音验证码发送失败", 0, 403,null,alertMsgEnum::voiceVerifyCodeFail);
       
    }

}

?>