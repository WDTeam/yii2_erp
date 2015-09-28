<?php
namespace common\components;

use yii\base\Object;
class Sms extends Object
{
    public $userId='';
    public $password='';
    
    /**
     * 发短信
     * @param string $mobiles 手机号，逗号分隔，可以是单个号码，最多100个
     * @param string $msg 内容，120字以内
     * @param number $iMobiCount    并发数量，最多100
     * @return curl_exec($ch)
     */
    public function send($mobiles, $msg, $iMobiCount=1)
    {
        $reqdata = http_build_query([
            'userId'=>$this->userId,
            'password'=>$this->password,
            'pszMobis'=>$mobiles,
            'pszMsg'=>$msg,
            'iMobiCount'=>$iMobiCount,
            'pszSubPort'=>'*'
        ]);
        $url='http://ws.montnets.com:9006/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?'.$reqdata;
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data =  curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}