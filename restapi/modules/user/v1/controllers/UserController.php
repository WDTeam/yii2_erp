<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/11
 * Time: 13:30
 */

namespace restapi\modules\user\v1\controllers;

use api\components\Controller;
use api\utils\SysCurl;
use api\utils\SysFunction;
use yii\web\ForbiddenHttpException;

/**
 * User controller
 */
class UserController extends Controller
{
    public $Url;
    public $NowTime;
    public $HTTPHEADER;

    public function beforeAction($action)
    {
        $before = parent::beforeAction($action);
        $this->Url = \Yii::$app->params['service']['user']['domain'].'user/user-info/view-by-telphone?';
        $this->NowTime = time();
        $this->HTTPHEADER = json_encode(["Appkey:{$this->appKey}"]);
        return $before;
    }

    public function actionIndex()
    {
        return $this->appVersion;
    }

    public function actionGetCaptcha()
    {
        $phone = \Yii::$app->request->get('phone');
        $captcha = rand(1000, 9999);
        \Yii::$app->cache->set("api_user_login_captcha_{$phone}", $captcha, 3600 * 24);
        //TODO 发送验证码手机短信。
        return \Yii::$app->cache->get("api_user_login_captcha_{$phone}");
    }

    /**
     * User controller
     * Check User Phone,if exists,return defined token,
     * else,insert User Msg,return defined token.
     */
    public function actionCheckUserPhone()
    {
        $UserPhone = !empty($_GET['UserPhone']) ? trim($_GET['UserPhone']) : 15810511209;
        $isMobile = SysFunction::checkMobile($UserPhone);
        if ($isMobile === -1) {
            throw new ForbiddenHttpException('User Phone is not valid', 100002);
        } elseif (empty(\Yii::$app->request->get('captcha'))) {
            throw new ForbiddenHttpException('Captcha is not valid', 100002);
        } elseif (\Yii::$app->request->get('captcha') != \Yii::$app->cache->get("api_user_login_captcha_{$UserPhone}")) {
            throw new ForbiddenHttpException('Captcha is invalid', 100002);
        } else {
            $this->HTTPHEADER = json_encode(["appkey:{$this->appKey}","time:{$this->NowTime}","sign:".md5("user/user-info/view-by-telphone".'-'.$this->appKey.'-'.$this->NowTime)]);
            $data = ['telphone' => $UserPhone, 'fields' => 'id', 'HTTPHEADER' => $this->HTTPHEADER];
            $ret = SysCurl::httpGet($this->Url, $data); //根据手机号查询用户
            if (empty($ret['data']['id'])) { //如果不存在则注册一个
                $this->HTTPHEADER = json_encode(["appkey:{$this->appKey}","time:{$this->NowTime}","sign:".md5("user/user-infos".'-'.$this->appKey.'-'.$this->NowTime)]);
                $data = ['telphone' => $UserPhone, 'HTTPHEADER' => $this->HTTPHEADER];
                $ret = SysCurl::httpPost(\Yii::$app->params['service']['user']['domain'].'user/user-infos', $data);
            }
            $UserId = $ret['data']['id'];
            $AuthString = $UserId . '@#@' . $this->NowTime;
            $ret = SysFunction::AuthCode($AuthString, 'ENCODE');
        }
        return $ret;
    }

    /**
     * User controller
     * Check User Token,if valid,return 1,else,-1.
     */
    public function actionCheckUserToken($flag = false)
    {
        $UserToken = !empty($_GET['UserToken']) ? trim($_GET['UserToken']) : '79f6rUNWEoNuOelgyPytOwjTDAWuttKCEI3NTabORBJTzUSj1ofkqU4JQXlJiK9gC8w';
        if (empty($UserToken)) {
            $ret = ['code' => 100001, 'msg' => 'User Token is empty', 'data' => []];
        }
        $UserTokenArray = explode('@#@', SysFunction::AuthCode($UserToken, 'DECODE'));
        if (!empty($UserTokenArray[0]) && ($UserTokenArray[1] + 86400) >= time()) {
            if ($flag === false) {
                $ret = ['code' => 100000, 'msg' => 'Success', 'data' => ['token' => $UserToken]];
            } else {
                $ret = ['code' => 100000, 'msg' => 'Success', 'data' => ['token' => $UserToken, 'UserId' => $UserTokenArray[0]]];
            }
        } else {
            $ret = ['code' => 100003, 'msg' => 'User Token is not valid', 'data' => []];
        }
        return $ret;
    }

    public function actionSetUserMsg()
    {
        $TokenArray = $this->actionCheckUserToken(true);
        $UserId = $TokenArray['data']['UserId'];
        $this->HTTPHEADER = json_encode(["appkey:{$this->appKey}","time:{$this->NowTime}","sign:".md5("user/user-info/update".'-'.$this->appKey.'-'.$this->NowTime)]);
        $data = \Yii::$app->request->post()+['HTTPHEADER' => $this->HTTPHEADER];
        $ret = SysCurl::httpPost(\Yii::$app->params['service']['user']['domain'].'user/user-info/update?id=' . $UserId, $data);
        $log = [
            'service_request_url'=>\Yii::$app->params['service']['user']['domain'].'user/user-info/update?id=' . $UserId,
            'service_request_data'=>$data,
            'service_respons'=>$ret
        ];
        \Yii::info(json_encode($log),'api_service');
        return $ret['data'];
    }

    public function actionGetUserMsg()
    {
        $TokenArray = $this->actionCheckUserToken(true);
        $UserId = $TokenArray['data']['UserId'];
        $this->HTTPHEADER = json_encode(["appkey:{$this->appKey}","time:{$this->NowTime}","sign:".md5("user/user-infos/".$UserId.'-'.$this->appKey.'-'.$this->NowTime)]);
        $data = ['HTTPHEADER' => $this->HTTPHEADER];
        $ret = SysCurl::httpGet(\Yii::$app->params['service']['user']['domain'].'user/user-infos/' . $UserId, $data);
        return $ret['data'];
    }
}
