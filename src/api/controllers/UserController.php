<?php
namespace api\controllers;

use Yii;
use api\components\Controller;
use api\utils\SysCurl;
use api\utils\SysFunction;

/**
 * User controller
 */
class UserController extends Controller
{
    public $Url;
    public $NowTime;
    public $HTTPHEADER;
    
    public function init() {
        $this->Url = 'http://service.1jiajie.com:80/user-info/view-by-telphone?';
        $this->NowTime = time();
        $this->HTTPHEADER = json_encode(['Appkey:4567']);
    }

    public function actionIndex()
    {
        echo 'Hello World!';
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
        if($isMobile === -1){
            $ret = ['code'=>100002,'msg'=>'User Phone is not valid','data'=>[]];
        }else{
            $data = ['telphone'=>$UserPhone,'fields'=>'id','HTTPHEADER'=>$this->HTTPHEADER];
            $ret = SysCurl::httpGet($this->Url,$data);
            if(empty($ret['data']['id'])){
                $data = ['telphone'=>$UserPhone,'HTTPHEADER'=>$this->HTTPHEADER];
                $ret = SysCurl::httpPost('http://service.1jiajie.com:80/user-infos',$data);
            }
            $UserId = $ret['data']['id'];
            $AuthString = $UserId.'@#@'.$this->NowTime;
            $ret = SysFunction::AuthCode($AuthString,'ENCODE');
        }
        print_r($ret);
    }
    /**
     * User controller
     * Check User Token,if valid,return 1,else,-1.
     */
    public function actionCheckUserToken($flag = false)
    {   
        $UserToken = !empty($_GET['UserToken']) ? trim($_GET['UserToken']) : '79f6rUNWEoNuOelgyPytOwjTDAWuttKCEI3NTabORBJTzUSj1ofkqU4JQXlJiK9gC8w';
        if(empty($UserToken)){
            $ret = ['code'=>100001,'msg'=>'User Token is empty','data'=>[]];
        }
        $UserTokenArray = explode('@#@',SysFunction::AuthCode($UserToken,'DECODE'));
        if(!empty($UserTokenArray[0]) && ($UserTokenArray[1] + 86400) >= time()){
            if($flag === false){
                $ret = ['code'=>100000,'msg'=>'Success','data'=>['token'=>$UserToken]];
            }else{
                $ret = ['code'=>100000,'msg'=>'Success','data'=>['token'=>$UserToken,'UserId'=>$UserTokenArray[0]]];
            }
        }else{
            $ret = ['code'=>100003,'msg'=>'User Token is not valid','data'=>[]];
        }
        return $ret;
    }
    public function actionSetUserMsg(){
        $TokenArray = $this->actionCheckUserToken(true);
        $UserId = $TokenArray['data']['UserId'];
        $data = ['gender'=>1,'name'=>'Mike','HTTPHEADER'=>$this->HTTPHEADER];
        $ret = SysCurl::httpPost('http://service.1jiajie.com:80/user-info/update?id='.$UserId,$data);
        print_r($ret);
    }
    public function actionGetUserMsg(){
        $TokenArray = $this->actionCheckUserToken(true);
        $UserId = $TokenArray['data']['UserId'];
        $data = ['HTTPHEADER'=>$this->HTTPHEADER];
        $ret = SysCurl::httpGet('http://service.1jiajie.com:80/user-infos/'.$UserId,$data);
        print_r($ret);
    }
}
