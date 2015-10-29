<?php

namespace common\models;

use Yii;

class Sms extends \yii\db\ActiveRecord
{
    public static function sendSms($mobile, $msg){
        if(empty($mobile)){
            throw new HttpException(401, '手机号不能为空');
        }
        if(empty($msg)){
            throw new HttpException(401, '不能发送空消息');
        }
        $reqdata = http_build_query([
            'userId'=>'J02356',
            'password'=>'556201',
            'pszMobis'=>$mobile,
            'pszMsg'=>$msg,
            'iMobiCount'=>'1',
            'pszSubPort'=>'*'
        ]);
        $url='http://ws.montnets.com:9006/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?'.$reqdata;
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $xml = curl_exec($ch);
        curl_close($ch);
        return self::getResult($xml);
    }
    
    private static function getResult($xml){
        $xmls = explode('>', $xml);
        $xmls = explode('<', $xmls[2]);
        $len = strlen($xmls[0]);
        if($len > 10 || $len < 25){
            return true;
        }
        return false;
    }
}
